<?php
    $path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
    include($path . 'wp-load.php');
    
    require_once(__DIR__ . '/vendor/autoload.php');
    
    use QuickBooksOnline\API\DataService\DataService;
    
    session_start();
    
    // Create SDK instance
    $config = include('config.php');
    /*
    * Retrieve the accessToken value from session variable
    */
    $sessionAccessToken = get_post_meta(1, 'sessionAccessToken', true);
    
    if (isset($sessionAccessToken)) {
        $accessToken = $sessionAccessToken['session'];
    } else {
        $accessToken = $_SESSION['sessionAccessToken'];
    }
    
    // $accessToken = $_SESSION['sessionAccessToken'];
    $dataService = DataService::Configure(array(
        'auth_mode' => 'oauth2',
        'ClientID' => $config['client_id'],
        'ClientSecret' => $config['client_secret'],
        'RedirectURI' => $config['oauth_redirect_uri'],
        'baseUrl' => "development",
        'refreshTokenKey' => $accessToken->getRefreshToken(),
        'QBORealmID' => "The Company ID which the app wants to access",
    ));
    
    /*
     * Update the OAuth2Token of the dataService object
     */
    $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
    $refreshedAccessTokenObj = $OAuth2LoginHelper->refreshToken();
    $dataService->updateOAuth2Token($refreshedAccessTokenObj);
    
    $_SESSION['sessionAccessToken'] = $refreshedAccessTokenObj;
    
    update_post_meta(1, 'refreshTokenKey', $accessToken->getRefreshToken());
    update_post_meta(1, 'sessionAccessToken', array('session' => $accessToken));
    
    $accessTokenQB = get_post_meta(1, 'accessTokenQB', true);
    $sessionAccessToken = get_post_meta(1, 'sessionAccessToken', true);
    
    print_r($refreshedAccessTokenObj);
    return $refreshedAccessTokenObj;
   
