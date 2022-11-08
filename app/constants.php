<?php

define('DT_LENGTH', '[ 50, 100, 250, 500 ]');

define('YOPAY_URL', 'https://paymentsapi2.yo.co.ug/ybs/task.php');
define('YOPAY_TEST_URL', 'https://paymentsdev1.yo.co.ug/yopaytest/task.php');

define('NIRA_TEST_URL', 'http://196.0.118.1:8080/pilatusp2-tpi2-ws/ThirdPartyInterfaceNewWS?wsdl');
define('NIRA_PROD_URL', 'http://192.168.14.126:14460/pilatusp2-tpi2-ws/ThirdPartyInterfaceNewWS?wsdl');

const UNDEFINED_DATA = '!@#$%';

// const SCORE_INTERCEPT = -1.537500;
// const closed_account_12m = 0.109910;
// const accounts_ever_30days_in_arrears_2yrs =  0.294960;
// const accounts_ever_greater_than_90_days_in_arrears_6months = 0.571130;
// const total_current_balance_amount_in_the_last_24month = 0.384720;
// const closed_unsecured_loans = 0.415290;
// const credit_applications_12_months = 0.874640;
// const value_of_credit_applications_12months = 0.958680;
// const banks_borrowed_from = 0.646880;
// const ratio_of_12month_to_24month_balance =  0.168400;

const MAX_INDIVIDUAL_SCORE = 736;
const MIN_INDIVIDUAL_SCORE = 224;

// image-charts API account
const IC_ACCOUNT_ID = 'nsnajibullah';
const IC_SECRET_KEY = '129C8F4F-E11D-41E8-A5E0-7E003C4B0C7F';