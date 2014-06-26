<?php

/**
 *  Salesforce API Class
 *  @author Hardik Shah<hardiks059@gmail.com>
 */
class apiSalesforce {

    public $instance_url = "https://cs17.salesforce.com";
    public $version_url = "/services/data/v28.0/";
    public $endPoint = "";
    public $access_token = "fasMyYY5_awu9lJden.YI_VW2GYgQGSYjsaO3vfupFRU0Afhk.zQj2lKDIBUUYfy_nZy8z4HfNuA";
    public $params = array();
    public $noHeader = false;
    public $method = "";
    public $partner_wsdl = "partner_sandbox.wsdl.xml";
    public $client_id = "Cqaop3uW9dNm4mL_bGcSyPYb9_nEyRX3E0iH93x7nO26PGYbZ0ULijsBp4hYMTan.NjRV";
    public $client_secret = "0617523991";
    public $username = "temp@my-sfdc.com";
    public $password = "Hardik1@#4";
    public $password_secret = "asdflkj387asfasdf"; // this needs to be generated once : sandbox
    public $client_id_live = "vnF6UUNkzDFECNbCmrnwW5yixmFRSeXW_qJh8Dn4LhL9YBEgDz6vJp3leSxC2B54i6RE8s";
    public $client_secret_live = "77387197";
    public $username_live = "temp@my-sfdc.com";
    public $password_live = "Hardik1@#4";
    public $instance_url_live = "https://login.salesforce.com";
    //public $password_secret_live = "GfDHrPLeJ4wErGSe1hVh4TTT"; // this needs to be generated once : live
    public $password_secret_live = "ojhLedgrxHbDx3MRELhnfngA"; // this needs to be generated once : live
    public $partner_wsdl_live = "partner.wsdl.xml";
    public $status = "";

    /**
     * class constructor
     */
    public function __construct() {
        // overwrite live SFDC credentials for live instance
        if (!_isLocalMachine() ) {
            $this->username = $this->username_live;
            $this->password = $this->password_live;
            $this->client_id = $this->client_id_live;
            $this->client_secret = $this->client_secret_live;
            $this->password_secret = $this->password_secret_live;
            $this->instance_url = $this->instance_url_live;
            $this->partner_wsdl = $this->partner_wsdl_live;
        }
        $this->doSFAuth();
    }

    public function doSFAuth() {
        $url = $this->instance_url . "/services/oauth2/token";
        $this->method = "post_plain";
        $this->noHeader = true;
        $this->params = "";
        $this->params .= "grant_type=password"
                . "&client_id=" . $this->client_id
                . "&client_secret=" . $this->client_secret
                . "&username=" . urlencode($this->username)
                . "&password=" . urlencode($this->password . $this->password_secret);
        $data = $this->doCall($url);
        $this->access_token = $data['access_token'];
        $this->instance_url = $data['instance_url'];

        $this->noHeader = false;
        $this->method = "GET";
    }

    public function doCall($url) {

        //d($url);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if (!$this->noHeader) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: OAuth {$this->access_token}", "Content-type: application/json"));
        }

        if ($this->method == "post") {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->params));
        }
        if ($this->method == "post_plain") {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->params);
        }

        if (in_array($this->method, array('PATCH', 'DELETE'))) {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->params));
        }


        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        $json_response = curl_exec($curl);
        $this->status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return $response = json_decode($json_response, true);
    }

    public function getObjectMetaData($objectName) {
        $this->method = "GET";
        $this->endPoint = "sobjects/{$objectName}/";
        $url = $this->prepareURL();
        return $this->doCall($url);
    }

    public function getLayouts($objectName) {
        $this->method = "GET";
        $this->endPoint = "sobjects/{$objectName}/describe/layouts/";
        $url = $this->prepareURL();
        return $this->doCall($url);
    }

    public function createAccount($data) {
        $this->params = $data;
        $this->endPoint = "sobjects/Account/";
        $this->method = "post";

        $url = $this->prepareURL();
        return $this->doCall($url);
    }

    public function createCalllog($data) {
        $this->params = $data;
        $this->endPoint = "sobjects/Task/";
        $this->method = "post";

        $url = $this->prepareURL();
        return $this->doCall($url);
    }

    public function getContact($id) {
        $this->params = array();
        $this->endPoint = "sobjects/Contact/{$id}";
        $this->method = "GET";

        $url = $this->prepareURL();
        return $this->doCall($url);
    }

    public function getAccount($id) {
        $this->params = array();
        $this->endPoint = "sobjects/Account/{$id}";
        $this->method = "GET";

        $url = $this->prepareURL();
        return $this->doCall($url);
    }

    public function createContact($data) {
        $this->params = $data;
        $this->endPoint = "sobjects/Contact/";
        $this->method = "post";

        $url = $this->prepareURL();
        return $this->doCall($url);
    }

    public function createCustTrips($data) {
        $this->params = $data;
        $this->endPoint = "sobjects/CustomerTrip1__c/";
        $this->method = "post";

        $url = $this->prepareURL();
        return $this->doCall($url);
    }

    public function getLead($id) {
        $this->params = array();
        $this->endPoint = "sobjects/Lead/{$id}";
        $this->method = "GET";

        $url = $this->prepareURL();
        return $this->doCall($url);
    }

    public function createLead($data) {
        $this->params = $data;
        $this->endPoint = "sobjects/Lead/";
        $this->method = "post";

        $url = $this->prepareURL();
        return $this->doCall($url);
    }

    public function updateLead($data, $id) {
        $this->params = $data;
        $this->endPoint = "sobjects/Lead/{$id}";
        $this->method = "PATCH";

        $url = $this->prepareURL();
        return $this->doCall($url);
    }

    public function updateContact($data, $id) {
        $this->params = $data;
        $this->endPoint = "sobjects/Contact/{$id}";
        $this->method = "PATCH";

        $url = $this->prepareURL();
        return $this->doCall($url);
    }

    public function doQuery($query) {
        $this->method = "GET";
        $this->endPoint = "query?q=" . urlencode($query);
        $url = $this->prepareURL();
        return $this->docall($url);
    }

    public function prepareURL() {
        return $this->instance_url . $this->version_url . $this->endPoint;
    }

    public static function getSFAcountNumber($la_account_number) {
        $query = "select sfAcctId  from limoaccountinfo where AcctNumber = '{$la_account_number}' ";
        $data = qs($query);
        return $data['sfAcctId'];
    }

    public static function getSFAccoutName($la_account_number) {
        $query = "select IF(ContCompany='',CONCAT(ContFName,' ',ContLName),ContCompany) as cName  from limoaccountinfo where AcctNumber = '{$la_account_number}' ";
        $data = qs($query);
        return $data['cName'];
    }

    public static function CustomerStatus($tripDate) {
        $dStart = new DateTime($tripDate);
        $dEnd = new DateTime();
        $dDiff = $dEnd->diff($dStart);
        return $dDiff->y > 0 ? 'Inactive' : 'Active';
    }

    /**
     * Get All the account information from LA API
     * and import them into limoaccountinfo database
     * 
     */
    public function importLAAccounts() {

        # get all customer accounts
        $data = q("select * from   triprevenuesummary WHERE laAccountRetrieved = '' or laAccountRetrieved = '0' group by account_no ");

        _ls("Found " . count($data) . " accounts ");
        $apiLimo = new apiLimo();


        foreach ($data as $ed) {

            // if account exists, than simply do not ping LA
            $exist_data = qs("select AcctNumber,id from limoaccountinfo where AcctNumber = '{$ed['account_no']}' ");

            if (count($exist_data) > 0) {
                d($exist_data);
                qu('triprevenuesummary', array('laAccountRetrieved' => $exist_data['id']), " account_no = '{$ed['account_no']}' ");
                _ls("Account detail already retrieved from LA. {$ed['account_no']}: Skipping ");
                continue;
            }

            $accountLA = $apiLimo->GetAccountsByAcctNumber($ed['account_no']);
            $accountLA = (array) $accountLA->GetAccountsByAcctNumberResult->Accounts->AccountInfo;
            $insert_data = array('IdCont',
                'IdComp',
                'IdParent',
                'ContAcctNumber',
                'ContFName',
                'ContLName',
                'ContCompany',
                'ContAddr1',
                'ContAddr2',
                'ContCity',
                'ContState',
                'ContZip',
                'ContPhone1',
                'ContPhone2',
                'ContPhone3',
                'ContPhone4',
                'ContPhone5',
                'ContFax',
                'ContFax1',
                'ContEmail',
                'IdAgent',
                'AcctID',
                'ParentId',
                'CompanyId',
                'AcctNumber',
                'DateCreated',
                'PaymentMethod');

            $db_insert_data = array();
            foreach ($insert_data as $ef) {
                $db_insert_data[$ef] = $accountLA[$ef];
            }
            qi('limoaccountinfo', $db_insert_data, 'REPLACE');
            _ls("imported {$db_insert_data['AcctID']}");
        }

        _ls('Import of Accounts Done');
    }

    public function createSFAccounts() {
        $accounts = q(" select * from limoaccountinfo where status = 'Active'  ORDER BY id desc  ");
        _ls("Found " . count($accounts) . " Accounts");


        foreach ($accounts as $ea) {
            _ls('---');
            qu("limoaccountinfo", array('sfMoved' => "1"), " id = '{$ea['id']}' ");
            _ls("Moving {$ea['AcctNumber']} ");

            $acctName = $ea['ContCompany'] ? $ea['ContCompany'] : $ea['ContFName'] . " " . $ea['ContLName'];
            $data = array(
                'Name' => $acctName,
                'Type' => "Customer",
                'Phone' => $ea['ContPhone1'],
                'Phone3__c' => $ea['ContPhone2'],
                'Phone2__c' => $ea['ContPhone3'],
                'LimoAnywhere_Account_Number__c' => $ea['AcctNumber'],
                'BillingCountryCode' => "US",
                'BillingStreet' => $ea['ContAddr1'] . " " . $ea['ContAddr2'],
                'BillingCity' => $ea['ContCity'],
                'BillingStateCode' => $ea['ContState'],
                'Number_of_trips__c' => $ea['tripTotal'],
                'Total_Revenue__c' => $ea['revenueTotal'],
                'Total_Outstanding_Revenue__c' => $ea['revenueOutstrandingTotal'],
                //'LimoAnywhere_Create_Date__c' => date('Y-m-d', strtotime($ea['DateCreated'])),
                'BillingPostalCode' => $ea['ContZip'],
                //'Date_of_last_trip__c' => date("c", strtotime($ea['lastTrip'])),
                'Account_Status__c' => $ea['status'],
                'OwnerId' => '005i0000001gWQe'
            );

            if ($ea['DateCreated'] != '0000-00-00 00:00:00') {
                $data['LimoAnywhere_Create_Date__c'] = date('Y-m-d', strtotime($ea['DateCreated']));
            }
            if ($ea['lastTrip'] != '0000-00-00 00:00:00') {
                $data['Date_of_last_trip__c'] = date("c", strtotime($ea['lastTrip']));
            }

            $sfQuery = "select Id from Account where Name = '{$acctName}' AND LimoAnywhere_Account_Number__c = '{$ea['AcctNumber']}'  ";
            $sfData = $this->doQuery($sfQuery);
            _ls("Querying to SF: {$each_data['sfAcctId']}");

            if ($sfData['totalSize'] == '1') {
                _ls('Data Exists');
                $sfAcctId = $sfData['records']['0']['Id'];
                _ls("Account ID : " . $sfAcctId);
                _ls($sfAcctId);
                $data = $this->updateAccountData($data, $sfAcctId);
            } else {
                _ls('New Data');
                $data = $this->createAccount($data);
                $sfAcctId = $data['id'];
            }

            var_dump($data);

            if ($sfAcctId) {
                qu("limoaccountinfo", array('sfAcctId' => $sfAcctId), " id = '{$ea['id']}' ");
                _ls("Moved Successfully");
            } else {
                _ls('Move Failed');
            }
        }
        _ls("Account Creation At SF Done");
    }

    public function updateAccountData($data, $id) {
        $this->params = $data;
        $this->endPoint = "sobjects/Account/{$id}";
        $this->method = "PATCH";

        $url = $this->prepareURL();
        return $this->doCall($url);
    }

    /**
     * Assign LA accounts ids to remaining invoices 
     */
    public function assignLAAccounts() {
        $apiLimo = new apiLimo();
        $data = q("select * from triprevenuesummary where account_no = '' ");
        foreach ($data as $er) {
            $code = compatibleTripCode($er['tripCode']);
            $id = $er['tripId'];
            $reservationDetails = $apiLimo->getReservation($code, $id);
            $customer_account = $reservationDetails->GetReservationResult->Ride->BillingAccountNumber;
            qu("triprevenuesummary", array('account_no' => $customer_account), " id = '{$er['id']}' ");
        }
    }

    /**
     * Create contacts at SF
     */
    public function createSFContact() {

        //$contacts = q("select * from triprevenuesummary WHERE sfContactCreated = '0'    ");
        $contacts = q("select * from triprevenuesummary  WHERE  sfContactId = ''    ");

        _ls("Found " . count($contacts) . " Contacts");

        define('SOAP_CLIENT_BASEDIR', _PATH . 'lib/SFDC');

        require_once (SOAP_CLIENT_BASEDIR . '/SforcePartnerClient.php');
        require_once (SOAP_CLIENT_BASEDIR . '/SforceHeaderOptions.php');

        $mySforceConnection = new SforcePartnerClient();
        $mySoapClient = $mySforceConnection->createConnection(SOAP_CLIENT_BASEDIR . '/' . $this->partner_wsdl);
        $mylogin = $mySforceConnection->login($this->username, $this->password . $this->password_secret);

        foreach ($contacts as $ea) {
            _ls("--");

            qu("triprevenuesummary", array('sfContactCreated' => "1"), " id = '{$ea['id']}' ");
            $email = $ea['PassengerEmail'] ? " AND Email = '{$ea['PassengerEmail']}'  " : "";

            // special case if last name is blank and both is provided as first name
            // i.e. first name = Joseph Bonnie
            if (trim($ea['PassengerLastName']) == '') {
                $ea['PassengerLastName'] = end(explode(" ", $ea['PassengerFirstName']));
            }

            // special case if emails are CSV
            // i.e. atoguchi@mgmresorts.com, jpn@mgmresorts.com
            $ea['PassengerEmail'] = explode(",", $ea['PassengerEmail']);
            $ea['PassengerEmail'] = $ea['PassengerEmail']['0'];

            $phone_number = getTripId($ea['tripCode'], true);
            $data = array(
                'FirstName' => $ea['PassengerFirstName'],
                'LastName' => $ea['PassengerLastName'],
                'Email' => $ea['PassengerEmail'],
                'HomePhone' => $phone_number['passengerPhone'],
                'Record_Type__c' => 'Customer',
                'OwnerId' => '005i0000001gWQe',
                'Status__c' => apiSalesforce::CustomerStatus($ea['tripDate']),
                'AccountId' => apiSalesforce::getSFAcountNumber($ea['account_no'])
            );
            d($data);
            $recordExist = $this->doQuery(" Select FirstName,Id from Contact where FirstName = '{$ea['PassengerFirstName']}' AND LastName = '{$ea['PassengerLastName']}' {$email} ");
            d($recordExist);

            if (count($recordExist['records'])) {
                _ls('Record Exists for ' . $ea['PassengerFirstName'] . " " . $ea['PassengerLastName']);
                $sfContId = $recordExist['records']['0']['Id'];
                $result = $this->updateContact($data, $sfContId);
                qu("triprevenuesummary", array('sfContactId' => $sfContId), " id = '{$ea['id']}' ");
                d($result);
            } else {
                $data = $this->createContact($data);
                if ($data['id']) {
                    $sfContId = $data['id'];
                    qu("triprevenuesummary", array('sfContactId' => $data['id']), " id = '{$ea['id']}' ");
                    _ls("Moved Successfully");
                } else {
                    _ls("Moved Failed");
                    d($data);
                }
                _ls('Contacts Created');
            }
            if (trim($ea['PassengerEmail'])) {
                // check for the lead conversion
                _ls('checking for leads conversion with email: ' . $ea['PassengerEmail']);
                $query = "SELECT Email,Id FROM Lead where Email = '{$ea['PassengerEmail']}' AND Status != 'Engaged' ";
                $sfData = $this->doQuery($query);
                d($sfData);

                if ($sfData['totalSize']) {
                    _ls('lead found');
                    $sObject = new stdclass();
                    $sObject->accountId = apiSalesforce::getSFAcountNumber($ea['account_no']);
                    $sObject->contactId = $sfContId;
                    $sObject->leadId = $sfData['records']['0']['Id'];
                    $sObject->convertedStatus = "Engaged";
                    $sObject->ownerId = "005i0000001gWQe";
                    $sObject->doNotCreateOpportunity = true;
                    $sObject->overwriteLeadSource = false;
                    $sObject->sendNotificationEmail = false;
                    $params = (array) $sObject;
                    d($params);
                    try {
                        $response = $mySforceConnection->convertLead($params);
                    } catch (Exception $e) {
                        //echo $mySforceConnection->getLastRequest();
                        _ls("Error: " . $e->faultstring);
                    }

                    d($response);
                    _ls('lead conversion sent');
                } else {
                    _ls('No existing leads');
                }
            }
        }
        _ls('end of the function');
    }

    public function updateOpportunity($data, $id) {
        $this->params = $data;
        $this->endPoint = "sobjects/Opportunity/{$id}";
        $this->method = "PATCH";

        $url = $this->prepareURL();
        return $this->doCall($url);
    }

    public function createOpportunity($data) {
        $this->params = $data;
        $this->endPoint = "sobjects/Opportunity/";
        $this->method = "post";

        $url = $this->prepareURL();
        return $this->doCall($url);
    }

    public function createSFOpportunity() {

        _ls("Starting opportunity import");

        $revenueInfo = q("select * from triprevenuesummary where sfOppCreated = '0' AND sfContactCreated = '1' AND PaymentStatus = 'PAID' ");

        _ls("Found " . count($revenueInfo) . " Records ");


        foreach ($revenueInfo as $er) {
            // check whether an opportunity exist for this contact ?
            // if yes, then add the revenue into it
            // if no, then create one

            _ls('Importing Opportunity for ' . $er['account_no']);
            qu('triprevenuesummary', array('sfOppCreated' => '1'), " id = '{$er['id']}'  ");
            $accountId = apiSalesforce::getSFAcountNumber($er['account_no']);
            $query = " select AccountId,Name,Amount,Type,Id from Opportunity where AccountId = '{$accountId}' AND Type = 'Existing Business' ";
            $data = $this->doQuery($query);

            _ls("Total opportunity for this account #" . ($data['totalSize']));

            if ($data['totalSize'] == '0') {
                // create an opportunity under this account
                $oppData = array();
                $oppData['Name'] = "Total Opportunity - " . apiSalesforce::getSFAccoutName($er['account_no']);
                $oppData['AccountId'] = $accountId;
                $oppData['Type'] = 'Existing Business';
                $oppData['CloseDate'] = date('Y-m-d', strtotime("+1 Year"));
                $oppData['StageName'] = 'Closed Won';
                $oppData['Amount'] = $er['BaseRate'] ? $er['BaseRate'] : $er['PerHourRateTotal'];
                $result = $this->createOpportunity($oppData);
                if ($result['success'] == '1') {
                    $id = $result['id'];
                    qu("triprevenuesummary", array('sfOppId' => $id), " id = '{$er['id']}' ");
                }
                _ls('Created New Existing Biz Opportunity');
            } else {
                // update the amount
                $existing_amount = $data['records'][0]['Amount'];
                $oppId = $data['records'][0]['Id'];
                $oppData = array();
                $current_amount = $er['BaseRate'] ? $er['BaseRate'] : $er['PerHourRateTotal'];
                $oppData['Amount'] = $current_amount + $existing_amount;
                $result = $this->updateOpportunity($oppData, $oppId);
                qu("triprevenuesummary", array('sfOppId' => $oppId), " id = '{$er['id']}' ");
                _ls("Update existing biz opportunity");
            }

            _ls("Looking for opportunities with new biz");
            $query = " select AccountId,Name,Amount,Type,CloseDate,Id from Opportunity where AccountId = '{$accountId}' AND Type = 'New Business' ";
            $data = $this->doQuery($query);

            _ls("Total opportunity for new business #" . ($data['totalSize']));

            if ($data['totalSize'] == '1') {
                $date = $data['records']['0']['CloseDate'];

                $dStart = new DateTime($date);
                $dEnd = new DateTime();
                $dDiff = $dStart->diff($dEnd);
                if ($dDiff->format('%R') == "-") {
                    $existing_amount = $data['records'][0]['Amount'];
                    $oppId = $data['records'][0]['Id'];
                    $oppData = array();
                    $current_amount = $er['BaseRate'] ? $er['BaseRate'] : $er['PerHourRateTotal'];
                    $oppData['Amount'] = $current_amount + $existing_amount;
                    $result = $this->updateOpportunity($oppData, $oppId);
                    _ls("Updated new biz opportunity");
                } else {
                    _ls("No need to update new biz opportunity");
                }
            } else {
                _ls("No New Biz opportunity");
            }
        }
    }

    public function updateCurrentContact() {

        // get all the customers
        $data = q("select sfContactId from triprevenuesummary where sfContactId != '' group by sfContactId ");

        foreach ($data as $each_data) {
            $this->params = array();
            $this->params['Record_Type__c'] = 'Passenger';
            $this->endPoint = "sobjects/Contact/{$each_data['sfContactId']}";
            $this->method = "PATCH";
            $url = $this->prepareURL();
            $this->doCall($url);
            _ls($each_data['sfContactId']);
        }
        _ls("Done" . count($data));
    }

    public function createBillingContact() {
        // Get all the billing account
        $data = q(" SELECT * FROM `limoaccountinfo` WHERE sfAcctId != '' AND ContEmail != '' GROUP BY `sfAcctId`  ");
        _ls("found total" . count($data) . " Records ");
        $sfDataExists = 0;
        $sfNewData = 0;
        foreach ($data as $each_data) {
            // query to salesforce
            $sfQuery = "select Id from Contact where FirstName = '{$each_data['ContFName']}' AND LastName = '{$each_data['ContLName']}' AND Email = '{$each_data['ContEmail']}' ";
            $sfData = $this->doQuery($sfQuery);
            _ls("Querying to SF: {$each_data['sfAcctId']}");

            if ($sfData ['totalSize'] == '1') {
                $sfDataExists++;
            } else {

                $data = array(
                    'FirstName' => $each_data['ContFName'],
                    'LastName' => $each_data['ContLName'],
                    'Email' => $each_data['ContEmail'],
                    'HomePhone' => $each_data['ContPhone3'],
                    'MobilePhone' => $each_data['ContPhone2'],
                    'Work_Phone__c' => $each_data['ContPhone1'],
                    'Record_Type__c' => 'Billing',
                    'OwnerId' => '005i0000001gWQe',
                    'Status__c' => apiSalesforce::CustomerStatus($each_data['lastTrip']),
                    'AccountId' => $each_data['sfAcctId']
                );

                $data = $this->createContact($data);
                d($data);
                _ls("Created New Contaced");

                $sfNewData++;
            }
        }

        _ls("Existing Data" . $sfDataExists);
        _ls("New Data" . $sfNewData);
    }

    // Update revenue
    public function updateRevenues() {

        $data = q(" SELECT * FROM `limoaccountinfo`  ");

        //$table = 'triprevenuesummary'; # For current trips
        $table = 'triprevenuesummary_old'; # for historical trips

        foreach ($data as $each_data) {
            $account_no = $each_data['AcctNumber'];
            _ls("Account No:" . $account_no);

            // for number of trips
            $query = "select count(id) as trips, sum(Total) as rev from  {$table} WHERE account_no = '{$account_no}'  group by account_no ";
            $ds_trips = qs($query);

            if (empty($ds_trips)) {
                _ls("No revenue information found");
                continue;
            }

            // for number of revenues
            $query = "select  sum(Total) as rev from  {$table} WHERE account_no = '{$account_no}' AND PaymentStatus = 'PAID' group by account_no ";
            $ds = qs($query);


            // for number of revenues
            $query = "select  sum(Total) as rev from  {$table} WHERE account_no = '{$account_no}' AND PaymentStatus != 'PAID' group by account_no ";
            $ds_unpaid = qs($query);


            // look for last date and status
//            $query = " select tripDate from {$table} where account_no = '{$account_no}' order by id desc limit 0,1 ";
//            $last_trip = qs($query);
//            $trip_date = $last_trip['tripDate'];
//            $status = self::CustomerStatus($trip_date);
            // may be salesforce have problem with number formatting - lets keep it on salesforce
//            $ds_unpaid['rev'] = $ds_unpaid['rev'] ? number_format($ds_unpaid['rev'], 2) : 0;
//            $ds['rev'] = $ds['rev'] ? number_format($ds['rev'], 2) : 0;
            //$data = array('revenueOutstrandingTotal' => $ds_unpaid['rev'], "lastTrip" => $trip_date, "status" => $status, 'revenueTotal' => $ds['rev'], 'tripTotal' => $ds_trips['trips']);
            $data = array('revenueOutstrandingTotal' => $ds_unpaid['rev'], 'revenueTotal' => $ds['rev'], 'tripTotal' => $ds_trips['trips']);
            d($data);
            qu("limoaccountinfo", $data, " id = '{$each_data['id']}' ");
            _ls("Record Updated: " . $dateCreated . "-" . $each_data['id']);
        }

        return;

//        $data = q(" SELECT * FROM `limoaccountinfo` WHERE sfAcctId != '' GROUP BY `sfAcctId`  ");
//        foreach ($data as $each_data) {
//            d($each_data);
//            $this->params = array(
//                'OwnerId' => '005i0000001gWQe'
////                'Number_of_trips__c' => $each_data['tripTotal'],
////                'Total_Revenue__c' => $each_data['revenueTotal'],
//                    //'LimoAnywhere_Create_Date__c' => date("Y-m-d", strtotime($each_data['DateCreated']))
//            );
//            $this->endPoint = "sobjects/Account/{$each_data['sfAcctId']}";
//            $this->method = "PATCH";
//
//            $url = $this->prepareURL();
//            $data = $this->doCall($url);
//            d($data);
//        }
    }

    public function updateCustomerType() {
        $data = q(" SELECT * FROM `limoaccountinfo` WHERE sfAcctId != '' AND contEmail != '' GROUP BY `sfAcctId`  ");
        $u_data = 0;
        $i_data = 0;
        foreach ($data as $each_data) {
            $query = "SELECT Id FROM Contact WHERE Email = '{$each_data['ContEmail']}' ";
            $ds = $this->doQuery($query);
            if ($ds['totalSize'] > 0) {
                //update contact
                $this->params = array(
                    'Record_Type__c' => 'Customer'
                );
                $this->endPoint = "sobjects/Contact/{$ds['records'][0]['Id']}";
                $this->method = "PATCH";

                $url = $this->prepareURL();
                $this->doCall($url);
                _ls("Updated  Customer" . $each_data['ContEmail']);
                $u_data++;
            } else {
                // create contact
                $ds = array(
                    'FirstName' => $each_data['ContFName'],
                    'LastName' => $each_data['ContLName'],
                    'Email' => $each_data['ContEmail'],
                    'HomePhone' => $each_data['ContPhone3'],
                    'Record_Type__c' => 'Customer',
                    'Status__c' => 'Active',
                    'AccountId' => $each_data['sfAcctId']
                );
                $this->createContact($ds);
                _ls("Created Contact: " . $each_data['ContEmail']);
                $i_data++;
            }
        }
        _ls("Update {$u_data}");
        _ls("Insert {$i_data}");
    }

    /**
     * update account status in the limoaccountinfo table by reading revenue data
     * also update last trip date
     */
    public function updateAccountStatus() {
        $query = "select * from triprevenuesummary_old  where account_no != '-1' order by tripDate ASC ";
        $data = q($query);
        foreach ($data as $each_data) {
            $tripDate = $each_data['tripTime'];
            $status = self::CustomerStatus($tripDate);
            $data = array('lastTrip' => $tripDate, 'status' => $status);
            _ls($tripDate . "_" . $status . "_" . $each_data['account_no']);
            qu("limoaccountinfo", $data, " AcctNumber = '{$each_data['account_no']}' ");
        }
    }

    /**
     * function to update last trip and account status at SFDC
     * mandatory to run updateAccountStatus
     */
    public function updateSFDCAccountStatus() {
        $query = "select * from limoaccountinfo  ";
        $data = q($query);


        foreach ($data as $each_data) {
            $this->params = array(
                'Account_Status__c' => $each_data['status'],
                'Date_of_last_trip__c' => date("c", strtotime($each_data['lastTrip']))
            );
            $this->endPoint = "sobjects/Account/{$each_data['sfAcctId']}";
            $this->method = "PATCH";

            $url = $this->prepareURL();
            $data = $this->doCall($url);
            d($data);
            _ls("Updated  Account" . $each_data['sfAcctId']);
        }
    }

    public function createYearlyOpportunity() {
        // we need to get all the opportunity
        // and bind them by year
        // get data from triprevenue table
        //$query = "select * from triprevenuesummary_old WHERE sfOppCreated = '0' AND account_no != '-1' LIMIT 0,10 ";
        //$query = "select * from triprevenuesummary_old WHERE account_no = '30806' and year(tripDate) != '2014' ";

        $query = " SELECT year( tripDate ) as tDate , sum( total ) as tRev , account_no
                    FROM `triprevenuesummary_old`
                    WHERE year( tripDate ) != '2014'  AND account_no != '-1'
                    GROUP BY account_no, year( tripDate )
                    ORDER BY `triprevenuesummary_old`.`account_no` ASC ";

        $data = q($query);

        _ls("found total records : " . count($data));

        foreach ($data as $er) {
            // creating or finding the opportunity

            $year = $er['tDate'];
            $opportunityName = "FY{$year}";

            _ls('Importing Opportunity for ' . $er['account_no']);

            $accountId = apiSalesforce::getSFAcountNumber($er['account_no']);
            if ($accountId) {
                $oppData = array();
                $oppData['Name'] = $opportunityName;
                $oppData['AccountId'] = $accountId;
                $oppData['Type'] = 'Existing Business';
                $oppData['CloseDate'] = "{$year}-12-31";
                $oppData['StageName'] = 'Closed Won';
                $oppData['Amount'] = $er['tRev'];
                $oppData['OwnerId'] = '005i0000001gWQe';
                $result = $this->createOpportunity($oppData);
                _ls('Created New Existing Biz Opportunity');
            } else {
                _ls('SKIPPED Opportunity');
            }
        }
        _ls("Creating yearly opportunity done");
    }

    public function importCustomTrips() {

// below script is used to import trip details into sfdc
        $query = "select * from triprevenuesummary where pu_city != '' AND do_city != '' AND sfCustonTrip = '0' order by id desc  LIMIT 0,10  ";
        $data = q($query);

        _ls("Found " . count($data) . " Records ");

        foreach ($data as $each_data) {

            qu('triprevenuesummary', array('sfCustonTrip' => 1), " id = {$each_data['id']} ");


//            // PassengerFirstName 	PassengerLastName
//
//            $sfQuery = "select Id from Contact where FirstName = '{$each_data['PassengerFirstName']}' AND LastName = '{$each_data['PassengerLastName']}'  ";
//            $sfData = $this->doQuery($sfQuery);
//
//            if ($sfData ['totalSize'] == '1') {
//                $contact_id = $sfData['records'][0]['Id'];
//                _ls('Contact Found - ' . $contact_id);
//                $insert_data = array();
//                $insert_data['Contact__c'] = $contact_id;
//                $insert_data['DropOff_City__c'] = $each_data['do_city'];
//                $insert_data['DropOff_State__c'] = $each_data['do_state'];
//                $insert_data['Pickup_City__c'] = $each_data['pu_city'];
//                $insert_data['Pickup_State__c'] = $each_data['pu_state'];
//                $insert_data['Total_Trip_Time__c'] = $each_data['PerHourRateUnits'];
//                $insert_data['TripDate__c'] = date('Y-m-d', strtotime($each_data['tripTime']));
//                $insert_data['Vehicle_Type__c'] = $each_data['carType'];
//                $insert_data['Trip_Time__c'] = date('c', strtotime($each_data['tripTime']));
//                $response = $this->createCustTrips($insert_data);
//                d($response);
//            } else {
//                _ls('Contact Doesnt Exists');
//            }
        }
    }

    public function updateRevenuesOldTrips() {
        $query = "SELECT *
                    FROM `limoaccountinfo` la
                    JOIN triprevenuesummary_old trs ON trs.account_no = la.AcctNumber
                    WHERE sfAcctId != ''
                    GROUP BY trs.account_no
                    LIMIT 750,250
                ";

        $data = q($query);

        $totalAccount = count($data);

        _ls("Found total {$totalAccount} records ");


        foreach ($data as $each_data) {
            $data = array(
                'LimoAnywhere_Account_Number__c' => $each_data['AcctNumber'],
                'Number_of_trips__c' => $each_data['tripTotal'],
                'Total_Revenue__c' => $each_data['revenueTotal'],
                'Total_Outstanding_Revenue__c' => $each_data['revenueOutstrandingTotal'],
                'Account_Status__c' => $each_data['status']
            );

            if ($each_data['DateCreated'] != '0000-00-00 00:00:00') {
                $data['LimoAnywhere_Create_Date__c'] = date('Y-m-d', strtotime($each_data['DateCreated']));
            }
            if ($each_data['lastTrip'] != '0000-00-00 00:00:00') {
                $data['Date_of_last_trip__c'] = date("c", strtotime($each_data['lastTrip']));
            }

            $sfAcctId = $each_data['sfAcctId'];
            _ls("Account ID : " . $sfAcctId);
            d($data);
            $data = $this->updateAccountData($data, $sfAcctId);
            var_dump($data);
            _ls('----');
        }
    }

}
