<?php

namespace App\Http\Controllers\API;

use App\Rules\Mobile;
use Illuminate\Http\Request;
use AlexaCRM\CRMToolkit\Settings;
use App\Http\Controllers\Controller;
use AlexaCRM\CRMToolkit\Client as OrganizationService;

class CRMController extends Controller
{
    /**
     * @var
     */
    protected $module;
    protected $service;
    protected $serviceSettings;

    /**
     * CRMController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $options = [
            'serverUrl' => 'https://pars.pendarnahad.com',
            'username' => 'web@elearnpars.org',
            'password' => 'qwe123$$',
            'authMode' => 'Federation',
            'ignoreSslErrors' => true
        ];
        $this->module = 'contact';
        $this->serviceSettings = new Settings($options);
        $this->service = new OrganizationService($this->serviceSettings);
    }

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index()
    {
        // retrieve all contacts
        $fields = array();
        $response = array();
        $data = $this->service->retrieveMultipleEntities($this->module);
        foreach ($data->Entities as $data_key => $data_item) {
            foreach ($data_item->propertyValues as $key => $item) {
                $fields[] = $key;
                $response[$data_key][$key] = $item['Value'];
            }
        }

        return $response;
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        $validate_data = array(
            'lastname' => ['required', 'string'],
            'emailaddress1' => ['required', 'email']
        );
        $this->validate($request, $validate_data);

        // create a new contact
        $contact = $this->service->entity($this->module);
        foreach($request->all() as $key => $item) {
            if (in_array($key, $this->getFields()))
                $contact->$key = $item;
        }
        $contactId = $contact->create();
        return response()->json($contactId, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $data = $this->service->entity($this->module, $id);
        $response = array();
        foreach ($data->propertyValues as $key => $item) {
            $response[$key] = $item['Value'];
        }
        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $data = $this->service->entity($this->module, $id);
        $response = array();
        foreach ($data->propertyValues as $key => $item) {
            $response[$key] = $item['Value'];
        }
        return response()->json($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     */
    public function update(Request $request, $id)
    {
        if ($id) {
            $contact = $this->service->entity($this->module, $id);
            if ($contact) {
                foreach($request->all() as $key => $item) {
                    if (in_array($key, $this->getFields()))
                        $contact->$key = $item;
                }
                #$contact->_ownerid_value = 'd55b21ea-afe3-eb11-a947-005056877d2b';
                #$contact->_owninguser_value = 'd55b21ea-afe3-eb11-a947-005056877d2b';
                $contact->update();
                return response()->json($id, 200);
            }

        }
        return response()->json($id, 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     */
    public function destroy($id)
    {
        return $id;
        $contact = $this->service->entity($this->module, $id);
        $contact->delete();
        return response()->json(null, 204);
    }

    /**
     * @param string $id
     * @throws \Exception
     */
    public function execute($id)
    {
        // execute an action
        $whoAmIResponse = $this->service->executeAction($id);
        return response()->json($whoAmIResponse, 200);
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return array("contactid", "emailaddress3", "emailaddress2", "emailaddress1", "address3_addresstypecode", "address1_city", "msdyn_gdproptoutname", "address3_line1", "address1_line1", "followemailname", "educationcodename", "haschildrencodename", "managerphone", "modifiedon", "aging90", "websiteurl", "msdyn_gdproptout", "address1_longitude", "donotpostalmail", "yomifullname", "company", "address1_addresstypecode", "entityimage_timestamp", "aging30", "address3_telephone1", "new_field", "statecodename", "address3_line3", "address2_county", "creditonhold", "transactioncurrencyidname", "new_mobile3", "donotbulkpostalmail", "entityimage_url", "address3_telephone3", "preferredsystemuseridyominame", "address1_shippingmethodcode", "paymenttermscode", "gendercode", "slainvokedid", "createdbyexternalpartyname", "address3_primarycontactname", "new_statename", "modifiedbyexternalpartyyominame", "originatingleadid", "new_id", "preferredsystemuseridname", "owningbusinessunit", "preferredappointmenttimecode", "new_sddress", "preferredappointmentdaycodename", "address2_stateorprovince", "participatesinworkflowname", "createdbyexternalpartyyominame", "timespentbymeonemailandmeetings", "mobilephone", "parentcustomeridyominame", "address2_country", "address2_line2", "accountid", "slaid", "preferredserviceid", "assistantphone", "owneridyominame", "parentcontactid", "statuscode", "address3_longitude", "onholdtime", "parentcontactidyominame", "address1_freighttermscode", "address3_fax", "creditlimit", "new_cityname", "birthdate", "address3_shippingmethodcodename", "originatingleadidname", "address1_utcoffset", "versionnumber", "donotsendmarketingmaterialname", "new_mainsourcename", "address3_shippingmethodcode", "address1_telephone1", "address3_upszone", "customertypecode", "donotbulkpostalmailname", "exchangerate", "address3_composite", "address2_line1", "modifiedbyexternalpartyname", "telephone3", "address2_city", "address3_line2", "marketingonly", "address1_addresstypecodename", "address2_latitude", "createdon", "donotbulkemail", "slaname", "createdbyyominame", "donotfax", "familystatuscodename", "address2_freighttermscodename", "aging90_base", "address1_composite", "firstname", "statuscodename", "preferredappointmenttimecodename", "donotsendmm", "callback", "address2_postalcode", "educationcode", "lastusedincampaign", "paymenttermscodename", "employeeid", "modifiedbyyominame", "governmentid", "isautocreate", "isbackofficecustomername", "address2_shippingmethodcodename", "address2_line3", "description", "modifiedby", "entityimage", "timezoneruleversionnumber", "marketingonlyname", "address1_county", "address2_telephone2", "shippingmethodcodename", "preferredcontactmethodcode", "modifiedonbehalfby", "donotemail", "aging60_base", "address3_freighttermscodename", "donotphonename", "pager", "address2_postofficebox", "address2_telephone1", "yomilastname", "address2_telephone3", "originatingleadidyominame", "preferredequipmentidname", "defaultpricelevelid", "address1_addressid", "traversedpath", "creditlimit_base", "annualincome", "owninguser", "address2_composite", "address2_name", "address1_country", "new_city", "middlename", "address3_telephone2", "entityimageid", "aging60", "territorycodename", "parentcustomeridname", "owneridname", "creditonholdname", "accountrolecode", "overriddencreatedon", "createdbyexternalparty", "suffix", "slainvokedidname", "shippingmethodcode", "participatesinworkflow", "customertypecodename", "owningteam", "new_mainsource", "address1_stateorprovince", "externaluseridentifier", "isprivatename", "preferredserviceidname", "address3_utcoffset", "modifiedonbehalfbyname", "createdonbehalfbyname", "address1_line3", "processid", "address1_freighttermscodename", "createdonbehalfby", "mastercontactidname", "new_state", "jobtitle", "nickname", "transactioncurrencyid", "managername", "isprivate", "address1_telephone2", "address1_telephone3", "address3_latitude", "isbackofficecustomer", "createdonbehalfbyyominame", "donotemailname", "childrensnames", "fax", "masterid", "mastercontactidyominame", "assistantname", "yomimiddlename", "ownerid", "address3_country", "address2_utcoffset", "stageid", "address2_fax", "new_partnername", "address3_freighttermscode", "merged", "owneridtype", "address3_county", "address2_longitude", "modifiedbyexternalparty", "ftpsiteurl", "preferredequipmentid", "createdbyname", "new_colorname", "donotphone", "address1_shippingmethodcodename", "accountidname", "address1_primarycontactname", "statecode", "lastonholdtime", "address1_line2", "yomifirstname", "createdby", "parentcustomerid", "address2_addresstypecode", "annualincome_base", "followemail", "accountrolecodename", "spousesname", "address3_name", "donotfaxname", "gendercodename", "address2_addresstypecodename", "salutation", "address1_postalcode", "leadsourcecodename", "customersizecode", "address2_upszone", "address3_postalcode", "donotbulkemailname", "defaultpricelevelidname", "preferredappointmentdaycode", "customersizecodename", "address2_addressid", "home2", "address3_stateorprovince", "anniversary", "importsequencenumber", "haschildrencode", "telephone2", "mergedname", "subscriptionid", "new_mobile2", "familystatuscode", "department", "preferredsystemuserid", "address3_city", "business2", "telephone1", "aging30_base", "address1_name", "address1_fax", "address1_latitude", "preferredcontactmethodcodename", "address2_shippingmethodcode", "address3_postofficebox", "parentcustomeridtype", "parentcontactidname", "modifiedbyname", "leadsourcecode", "address2_freighttermscode", "address1_upszone", "lastname", "address3_addresstypecodename", "accountidyominame", "territorycode", "modifiedonbehalfbyyominame", "donotpostalmailname", "new_color", "utcconversiontimezonecode", "numberofchildren", "address1_postofficebox", "address2_primarycontactname", "fullname", "address3_addressid", "contactid", "emailaddress3", "emailaddress2", "emailaddress1", "address3_addresstypecode", "address1_city", "msdyn_gdproptoutname", "address3_line1", "address1_line1", "followemailname", "educationcodename", "haschildrencodename", "managerphone", "modifiedon", "aging90", "websiteurl", "msdyn_gdproptout", "address1_longitude", "donotpostalmail", "yomifullname", "company", "address1_addresstypecode", "entityimage_timestamp", "aging30", "address3_telephone1", "new_field", "statecodename", "address3_line3", "address2_county", "creditonhold", "transactioncurrencyidname", "new_mobile3", "donotbulkpostalmail", "entityimage_url", "address3_telephone3", "preferredsystemuseridyominame", "address1_shippingmethodcode", "paymenttermscode", "gendercode", "slainvokedid", "createdbyexternalpartyname", "address3_primarycontactname", "new_statename", "modifiedbyexternalpartyyominame", "originatingleadid", "new_id", "preferredsystemuseridname", "owningbusinessunit", "preferredappointmenttimecode", "new_sddress", "preferredappointmentdaycodename", "address2_stateorprovince", "participatesinworkflowname", "createdbyexternalpartyyominame", "timespentbymeonemailandmeetings", "mobilephone", "parentcustomeridyominame", "address2_country", "address2_line2", "accountid", "slaid", "preferredserviceid", "assistantphone", "owneridyominame", "parentcontactid", "statuscode", "address3_longitude", "onholdtime", "parentcontactidyominame", "address1_freighttermscode", "address3_fax", "creditlimit", "new_cityname", "birthdate", "address3_shippingmethodcodename", "originatingleadidname", "address1_utcoffset", "versionnumber", "donotsendmarketingmaterialname", "new_mainsourcename", "address3_shippingmethodcode", "address1_telephone1", "address3_upszone", "customertypecode", "donotbulkpostalmailname", "exchangerate", "address3_composite", "address2_line1", "modifiedbyexternalpartyname", "telephone3", "address2_city", "address3_line2", "marketingonly", "address1_addresstypecodename", "address2_latitude", "createdon", "donotbulkemail", "slaname", "createdbyyominame", "donotfax", "familystatuscodename", "address2_freighttermscodename", "aging90_base", "address1_composite", "firstname", "statuscodename", "preferredappointmenttimecodename", "donotsendmm", "callback", "address2_postalcode", "educationcode", "lastusedincampaign", "paymenttermscodename", "employeeid", "modifiedbyyominame", "governmentid", "isautocreate", "isbackofficecustomername", "address2_shippingmethodcodename", "address2_line3", "description", "modifiedby", "entityimage", "timezoneruleversionnumber", "marketingonlyname", "address1_county", "address2_telephone2", "shippingmethodcodename", "preferredcontactmethodcode", "modifiedonbehalfby", "donotemail", "aging60_base", "address3_freighttermscodename", "donotphonename", "pager", "address2_postofficebox", "address2_telephone1", "yomilastname", "address2_telephone3", "originatingleadidyominame", "preferredequipmentidname", "defaultpricelevelid", "address1_addressid", "traversedpath", "creditlimit_base", "annualincome", "owninguser", "address2_composite", "address2_name", "address1_country", "new_city", "middlename", "address3_telephone2", "entityimageid", "aging60", "territorycodename", "parentcustomeridname", "owneridname", "creditonholdname", "accountrolecode", "overriddencreatedon", "createdbyexternalparty", "suffix", "slainvokedidname", "shippingmethodcode", "participatesinworkflow", "customertypecodename", "owningteam", "new_mainsource", "address1_stateorprovince", "externaluseridentifier", "isprivatename", "preferredserviceidname", "address3_utcoffset", "modifiedonbehalfbyname", "createdonbehalfbyname", "address1_line3", "processid", "address1_freighttermscodename", "createdonbehalfby", "mastercontactidname", "new_state", "jobtitle", "nickname", "transactioncurrencyid", "managername", "isprivate", "address1_telephone2", "address1_telephone3", "address3_latitude", "isbackofficecustomer", "createdonbehalfbyyominame", "donotemailname", "childrensnames", "fax", "masterid", "mastercontactidyominame", "assistantname", "yomimiddlename", "ownerid", "address3_country", "address2_utcoffset", "stageid", "address2_fax", "new_partnername", "address3_freighttermscode", "merged", "owneridtype", "address3_county", "address2_longitude", "modifiedbyexternalparty", "ftpsiteurl", "preferredequipmentid", "createdbyname", "new_colorname", "donotphone", "address1_shippingmethodcodename", "accountidname", "address1_primarycontactname", "statecode", "lastonholdtime", "address1_line2", "yomifirstname", "createdby", "parentcustomerid", "address2_addresstypecode", "annualincome_base", "followemail", "accountrolecodename", "spousesname", "address3_name", "donotfaxname", "gendercodename", "address2_addresstypecodename", "salutation", "address1_postalcode", "leadsourcecodename", "customersizecode", "address2_upszone", "address3_postalcode", "donotbulkemailname", "defaultpricelevelidname", "preferredappointmentdaycode", "customersizecodename", "address2_addressid", "home2", "address3_stateorprovince", "anniversary", "importsequencenumber", "haschildrencode", "telephone2", "mergedname", "subscriptionid", "new_mobile2", "familystatuscode", "department", "preferredsystemuserid", "address3_city", "business2", "telephone1", "aging30_base", "address1_name", "address1_fax", "address1_latitude", "preferredcontactmethodcodename", "address2_shippingmethodcode", "address3_postofficebox", "parentcustomeridtype", "parentcontactidname", "modifiedbyname", "leadsourcecode", "address2_freighttermscode", "address1_upszone", "lastname", "address3_addresstypecodename", "accountidyominame", "territorycode", "modifiedonbehalfbyyominame", "donotpostalmailname", "new_color", "utcconversiontimezonecode", "numberofchildren", "address1_postofficebox", "address2_primarycontactname", "fullname", "address3_addressid", "contactid", "emailaddress3", "emailaddress2", "emailaddress1", "address3_addresstypecode", "address1_city", "msdyn_gdproptoutname", "address3_line1", "address1_line1", "followemailname", "educationcodename", "haschildrencodename", "managerphone", "modifiedon", "aging90", "websiteurl", "msdyn_gdproptout", "address1_longitude", "donotpostalmail", "yomifullname", "company", "address1_addresstypecode", "entityimage_timestamp", "aging30", "address3_telephone1", "new_field", "statecodename", "address3_line3", "address2_county", "creditonhold", "transactioncurrencyidname", "new_mobile3", "donotbulkpostalmail", "entityimage_url", "address3_telephone3", "preferredsystemuseridyominame", "address1_shippingmethodcode", "paymenttermscode", "gendercode", "slainvokedid", "createdbyexternalpartyname", "address3_primarycontactname", "new_statename", "modifiedbyexternalpartyyominame", "originatingleadid", "new_id", "preferredsystemuseridname", "owningbusinessunit", "preferredappointmenttimecode", "new_sddress", "preferredappointmentdaycodename", "address2_stateorprovince", "participatesinworkflowname", "createdbyexternalpartyyominame", "timespentbymeonemailandmeetings", "mobilephone", "parentcustomeridyominame", "address2_country", "address2_line2", "accountid", "slaid", "preferredserviceid", "assistantphone", "owneridyominame", "parentcontactid", "statuscode", "address3_longitude", "onholdtime", "parentcontactidyominame", "address1_freighttermscode", "address3_fax", "creditlimit", "new_cityname", "birthdate", "address3_shippingmethodcodename", "originatingleadidname", "address1_utcoffset", "versionnumber", "donotsendmarketingmaterialname", "new_mainsourcename", "address3_shippingmethodcode", "address1_telephone1", "address3_upszone", "customertypecode", "donotbulkpostalmailname", "exchangerate", "address3_composite", "address2_line1", "modifiedbyexternalpartyname", "telephone3", "address2_city", "address3_line2", "marketingonly", "address1_addresstypecodename", "address2_latitude", "createdon", "donotbulkemail", "slaname", "createdbyyominame", "donotfax", "familystatuscodename", "address2_freighttermscodename", "aging90_base", "address1_composite", "firstname", "statuscodename", "preferredappointmenttimecodename", "donotsendmm", "callback", "address2_postalcode", "educationcode", "lastusedincampaign", "paymenttermscodename", "employeeid", "modifiedbyyominame", "governmentid", "isautocreate", "isbackofficecustomername", "address2_shippingmethodcodename", "address2_line3", "description", "modifiedby", "entityimage", "timezoneruleversionnumber", "marketingonlyname", "address1_county", "address2_telephone2", "shippingmethodcodename", "preferredcontactmethodcode", "modifiedonbehalfby", "donotemail", "aging60_base", "address3_freighttermscodename", "donotphonename", "pager", "address2_postofficebox", "address2_telephone1", "yomilastname", "address2_telephone3", "originatingleadidyominame", "preferredequipmentidname", "defaultpricelevelid", "address1_addressid", "traversedpath", "creditlimit_base", "annualincome", "owninguser", "address2_composite", "address2_name", "address1_country", "new_city", "middlename", "address3_telephone2", "entityimageid", "aging60", "territorycodename", "parentcustomeridname", "owneridname", "creditonholdname", "accountrolecode", "overriddencreatedon", "createdbyexternalparty", "suffix", "slainvokedidname", "shippingmethodcode", "participatesinworkflow", "customertypecodename", "owningteam", "new_mainsource", "address1_stateorprovince", "externaluseridentifier", "isprivatename", "preferredserviceidname", "address3_utcoffset", "modifiedonbehalfbyname", "createdonbehalfbyname", "address1_line3", "processid", "address1_freighttermscodename", "createdonbehalfby", "mastercontactidname", "new_state", "jobtitle", "nickname", "transactioncurrencyid", "managername", "isprivate", "address1_telephone2", "address1_telephone3", "address3_latitude", "isbackofficecustomer", "createdonbehalfbyyominame", "donotemailname", "childrensnames", "fax", "masterid", "mastercontactidyominame", "assistantname", "yomimiddlename", "ownerid", "address3_country", "address2_utcoffset", "stageid", "address2_fax", "new_partnername", "address3_freighttermscode", "merged", "owneridtype", "address3_county", "address2_longitude", "modifiedbyexternalparty", "ftpsiteurl", "preferredequipmentid", "createdbyname", "new_colorname", "donotphone", "address1_shippingmethodcodename", "accountidname", "address1_primarycontactname", "statecode", "lastonholdtime", "address1_line2", "yomifirstname", "createdby", "parentcustomerid", "address2_addresstypecode", "annualincome_base", "followemail", "accountrolecodename", "spousesname", "address3_name", "donotfaxname", "gendercodename", "address2_addresstypecodename", "salutation", "address1_postalcode", "leadsourcecodename", "customersizecode", "address2_upszone", "address3_postalcode", "donotbulkemailname", "defaultpricelevelidname", "preferredappointmentdaycode", "customersizecodename", "address2_addressid", "home2", "address3_stateorprovince", "anniversary", "importsequencenumber", "haschildrencode", "telephone2", "mergedname", "subscriptionid", "new_mobile2", "familystatuscode", "department", "preferredsystemuserid", "address3_city", "business2", "telephone1", "aging30_base", "address1_name", "address1_fax", "address1_latitude", "preferredcontactmethodcodename", "address2_shippingmethodcode", "address3_postofficebox", "parentcustomeridtype", "parentcontactidname", "modifiedbyname", "leadsourcecode", "address2_freighttermscode", "address1_upszone", "lastname", "address3_addresstypecodename", "accountidyominame", "territorycode", "modifiedonbehalfbyyominame", "donotpostalmailname", "new_color", "utcconversiontimezonecode", "numberofchildren", "address1_postofficebox", "address2_primarycontactname", "fullname", "address3_addressid");
    }
}
