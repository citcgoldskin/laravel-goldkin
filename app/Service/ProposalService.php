<?php

namespace App\Service;

use App\Models\Proposal;
use App\Models\User;
use DB;

class ProposalService
{
    public static function getPropsFrRecruit($rc_id)
    {
        return Proposal::where('pro_rc_id',$rc_id)
            ->with('proposalUser')->get();
   }
}
