<?php

namespace App\Service;

use App\Models\User;
use Square\Models\Address;
use Square\Models\Card;
use Square\Models\CreateCardRequest;
use Square\Models\CreateCustomerRequest;
use Square\Models\UpdateCustomerRequest;
use Square\SquareClient;
use Square\Exceptions\ApiException;
use Square\Environment;

use Carbon\Carbon;
use Log;
use Storage;
use Auth;
use DB;

class SquarePaymentService {
    /**/
    private $p_client;

    public function __construct()
    {
        Log::info("Get Square Client");

        if (app()->isProduction()) {
            $access_token = env('SQUARE_ACCESS_TOKEN');
            $environment = Environment::PRODUCTION;
        } else {
            $access_token = env('SQUARE_SANDBOX_ACCESS_TOKEN');
            $environment = Environment::SANDBOX;
        }
        Log::info("Environment: " . $environment);

        $obj_client = new SquareClient([
            'accessToken' => $access_token,
            'environment' => $environment,
        ]);

        try {
            Log::info("Got Client Object!");
            $this->p_client = $obj_client;
        } catch (ApiException $e) {
            Log::error("Failed in getting client object");
            Log::error($e->getMessage());

            abort(404);
        }
    }

    public function  getCustomerList($cursor=null, $limit=100, $sort_field='DEFAULT', $sort_order='ASC') {
        Log::info("Get Square Customers");

        $api_response = $this->p_client->getCustomersApi()->listCustomers($cursor, $limit, $sort_field, $sort_order);

        if ($api_response->isSuccess()) {
            $cursor = $api_response->getCursor();
            Log::info("Got Customer List!");
            return [
                'customers' => $api_response->getResult()->getCustomers($cursor, $limit, $sort_field, $sort_order),
                'cursor' => $api_response->getCursor()
            ];
        } else {
            Log::error("Failed in getting customer list");
            Log::error($api_response->getErrors());

            return [];
        }
    }

    public function getSquareCustomerByUser(User $obj_user) {
        Log::info("Get Square Customer");

        $square_customer = null;
        if($obj_user->squ_customer_id) {
            $square_customer = $this->retrieveCustomer($obj_user);
        }
        if(is_null($square_customer) || empty($obj_user->squ_customer_id)) {
            $square_customer =  $this->createCustomer($obj_user);
        }
        Log::info("Square Customer ID: " . $square_customer->getCustomer()->getId());
        Log::info("Found Square Customer");
        return $square_customer;
    }

    public function retrieveCustomer(User $obj_user) {
        Log::info("Retrieve Square Customer");
        Log::info("Square Customer ID: " . $obj_user->squ_customer_id);
        $api_response = $this->p_client->getCustomersApi()->retrieveCustomer($obj_user->squ_customer_id);
        if ($api_response->isSuccess()) {
            Log::info("Retrieved Square Customer");
            return $api_response->getResult();
        } else {
            Log::error("Failed in retrieving customer");
            Log::error($api_response->getErrors());
            return null;
        }
    }

    public function createCustomer(User $obj_user) {
        Log::info("Create Square Customer");

        $address =self::getSquareAddress($obj_user);

        $body = new CreateCustomerRequest();
        $body->setGivenName($obj_user->user_firstname);
        $body->setFamilyName($obj_user->user_lastname);
        $body->setEmailAddress($obj_user->email);
        $body->setAddress($address);
        $body->setPhoneNumber($obj_user->user_phone);
        $body->setReferenceId($obj_user->user_no);

        $api_response = $this->p_client->getCustomersApi()->createCustomer($body);

        if ($api_response->isSuccess()) {
            $squ_customer = $api_response->getResult();
            //Log::info("Reference ID: " . $squ_customer->getReferenceId());

            $obj_user->squ_customer_id = $squ_customer->getCustomer()->getId();
            $obj_user->save();

            Log::info("Square Customer ID: " . $obj_user->squ_customer_id);
            Log::info("Created Square Customer");
            return $squ_customer;
        } else {
            Log::error("Failed in creating customer");
            Log::error($api_response->getErrors());
            return null;
        }
    }

    public function updateCustomer(User $obj_user) {
        if($obj_user->squ_customer_id) {

            Log::info("Update Square Customer");
            Log::info("Square Customer ID: " . $obj_user->squ_customer_id);

            $square_customer = self::retrieveCustomer($obj_user);
            if($square_customer) {
                $address =self::getSquareAddress($obj_user);


                $body = new UpdateCustomerRequest();
                $body->setGivenName($obj_user->user_firstname);
                $body->setFamilyName($obj_user->user_lastname);
                $body->setEmailAddress($obj_user->email);
                $body->setAddress($address);
                $body->setPhoneNumber($obj_user->user_phone);
                $body->setReferenceId($obj_user->user_no);

                $api_response = $this->p_client->getCustomersApi()->updateCustomer($obj_user->squ_customer_id, $body);
                if ($api_response->isSuccess()) {
                    Log::info("Updated Square Customer");
                    return $api_response->getResult();
                } else {
                    Log::error("Failed in updating customer");
                    Log::error($api_response->getErrors());
                }
            } else {
                $obj_user->squ_customer_id = null;
                $obj_user->save();
                Log::info("Customer ID dose not exist");
                Log::info("Removed Square Customer ID from user table");
            }
        }

        return null;

    }

    public function deleteCustomer(User $obj_user) {
        Log::info("Delete Square Customer");
        Log::info("Square Customer ID: " . $obj_user->squ_customer_id);
        $api_response = $this->p_client->getCustomersApi()->deleteCustomer($obj_user->squ_customer_id);
        if ($api_response->isSuccess()) {
            //$result = $api_response->getResult();

            $obj_user->squ_customer_id = null;
            $obj_user->save();
            Log::info("Deleted Square Customer");
            return true;
        } else {
            Log::error("Failed in deleting customer");
            Log::error($api_response->getErrors());
            return false;
        }
    }

    public function addCreditCard(User $obj_user, $nonce, $card_holder) {
        Log::info("Add Credit Card");
        Log::info("Square Customer ID: " . $obj_user->squ_customer_id);
        $square_customer = $this->getSquareCustomerByUser($obj_user);
        if($square_customer) {
            $billing_address =self::getSquareAddress($obj_user);

            $card = new Card();
            $card->setCardholderName($card_holder);
            $card->setBillingAddress($billing_address);
            $card->setCustomerId($obj_user->squ_customer_id);
            //$card->setReferenceId('user-id-1');

            $card_id = 'C' . Carbon::now()->format('ymd') . uniqid();

            $body = new CreateCardRequest(
                $card_id,
                $nonce,
                $card
            );

            $api_response = $this->p_client->getCardsApi()->createCard($body);
            if ($api_response->isSuccess()) {
                Log::info("Added Created Card");
                $result =  $api_response->getResult();
                return CreditCardService::doAddCardCredit($obj_user, $result->getCard());
            } else {
                Log::error("Failed in adding card");
                Log::error($api_response->getErrors());
                return false;
            }
        } else {
            $obj_user->squ_customer_id = null;
            $obj_user->save();
            Log::info("Customer ID dose not exist");
            Log::info("Removed Square Customer ID from user table");
            return false;
        }
    }

    public function getSquareAddress(User $obj_user) {
        $address = new Address();
        $address->setAddressLine1($obj_user->user_county);
        $address->setAddressLine2($obj_user->user_village);
        $address->setLocality (is_object($obj_user->area) ? $obj_user->area->area_name : '');
        $address->setPostalCode($obj_user->user_mail);
        $address->setCountry('JP');
        return $address;
    }

}
