<?php

class myUser extends sfBasicSecurityUser
{
    public function hasCredential($credentials, $useAnd = true)
    {
        if (null === $this->credentials)
        {
            return false;
        }

        $username = $this->getAttribute('username');
        if( !empty($username) )
        {
            $acl = BackendUserTable::getACLByUsername($username);

            if (is_array($acl))
            {
                $credentialList = sfConfig::get('app_credentials');

                foreach($acl as $k => $v)
                {
                    if(isset($credentialList[$v-1]))
                    {
                        $this->addCredential($credentialList[$v-1]);
                    }
                }
            }
        }

        if (!is_array($credentials))
        {
            return in_array($credentials, $this->credentials);
        }

        // now we assume that $credentials is an array
        $test = false;

        foreach ($credentials as $credential)
        {
            // recursively check the credential with a switched AND/OR mode
            $test = $this->hasCredential($credential, $useAnd ? false : true);

            if ($useAnd)
            {
                $test = $test ? false : true;
            }

            if ($test) // either passed one in OR mode or failed one in AND mode
            {
                break; // the matter is settled
            }
        }

        if ($useAnd) // in AND mode we succeed if $test is false
        {
            $test = $test ? false : true;
        }

        return $test;
    }
}
