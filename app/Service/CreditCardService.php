<?php

namespace App\Service;
use App\Models\CardCredit;
use App\Models\User;

class CreditCardService
{

    //CardCredit
    public static function doAddCardCredit(User $obj_user, $card_data) {
        $data = [
            'cc_user_id' => $obj_user->id,
            'cc_square_card_id' => $card_data->getId(),
            'cc_is_default' => 1,
            'cc_data' => json_encode($card_data->jsonSerialize(true), true)
        ];

        self::resetDefaultCard($obj_user);
        if($obj_credit = CardCredit::create($data)) {
            return $obj_credit;
        } else {
            return null;
        }
    }

    public static function getCreditCards($user_id) {
        return CardCredit::orderBy('created_at')
            ->where('cc_user_id', $user_id)
            ->get();
    }

    public static function getDefaultCard($user_id) {
        return CardCredit::orderBy('created_at')
            ->where('cc_user_id', $user_id)
            ->where('cc_is_default', 1)
            ->first();
    }

    public static function resetDefaultCard(User $obj_user) {
        return CardCredit::where('cc_user_id', $obj_user->id)
            ->update([
                'cc_is_default' => 0
            ]);
    }

}
