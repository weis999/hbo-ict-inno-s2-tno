<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CredentialVerifyRequest extends Model
{
    protected $table = 'credential_verify_request';
    protected $fillable = ['id', 'callbackURL', 'credentialTypes'];
}
