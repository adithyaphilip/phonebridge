<?php
class Gcm extends CI_Model {
    function sendImageUrlMessage ( $params ) {
        $req = ['url','askerid','helperid'];
        $data = [];
        foreach($req as $key) {
            $data[$key] = $params[$key];
        }
        $this->load->model('User');
        $user_result = $this->User->getEntries(['userid'=>$data['helperid']],array('userid'));
        $gcmMessage['data'] = $data;
        $gcmMessage['ids'][] = $user_result[0]['gcmregid'];
        $this->sendGoogleCloudMessage($gcmMessage);
    }
    function sendOfferMessage ( $params ) {
        $req = ['askerid','helperid'];
        $data = [];
        foreach($req as $key) {
            $data[$key] = $params[$key];
        }
        $this->load->model('User');
        $user_result = $this->User->getEntries(['userid'=>$data['askerid']],array('userid'));
        $gcmMessage['data'] = $data;
        $gcmMessage['ids'][] = $user_result[0]['gcmregid'];
        $this->sendGoogleCloudMessage($gcmMessage);
    }
    function sendCoordMessage ( $params ) {
        $req = ['askerid','helperid','x','y'];
        $data = [];
        foreach($req as $key) {
            $data[$key] = $params[$key];
        }
        $this->load->model('User');
        $user_result = $this->User->getEntries(['userid'=>$data['askerid']],array('userid'));
        $gcmMessage['data'] = $data;
        echo(json_encode($gcmMessage['data']));
        $gcmMessage['ids'][] = $user_result[0]['gcmregid'];
        $this->sendGoogleCloudMessage($gcmMessage);
    }
    function sendGoogleCloudMessage( $params )
    {
        $data = $params['data'];
        $ids = $params['ids'];
        //------------------------------
        // Replace with real GCM API 
        // key from Google APIs Console
        // 
        // https://code.google.com/apis/console/
        //------------------------------

        $apiKey = 'AIzaSyAK4m_0pr8GeVxi71PTtn55B04ULPy61PI';

        //------------------------------
        // Define URL to GCM endpoint
        //------------------------------

        $url = 'https://android.googleapis.com/gcm/send';

        //------------------------------
        // Set GCM post variables
        // (Device IDs and push payload)
        //------------------------------

        $post = array(
                        'collapse_key'     => 'gcm',
                        'registration_ids'  => $ids,
                        'data'              => $data,
                        );

        //------------------------------
        // Set CURL request headers
        // (Authentication and type)
        //------------------------------

        $headers = array( 
                            'Authorization: key=' . $apiKey,
                            'Content-Type: application/json'
                        );

        //------------------------------
        // Initialize curl handle
        //------------------------------

        $ch = curl_init();

        //------------------------------
        // Set URL to GCM endpoint
        //------------------------------

        curl_setopt( $ch, CURLOPT_URL, $url );

        //------------------------------
        // Set request method to POST
        //------------------------------

        curl_setopt( $ch, CURLOPT_POST, true );

        //------------------------------
        // Set our custom headers
        //------------------------------

        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

        //------------------------------
        // Get the response back as 
        // string instead of printing it
        //------------------------------

        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        //------------------------------
        // Set post data as JSON
        //------------------------------

        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $post ) );

        //------------------------------
        // Actually send the push!
        //------------------------------

        $result = curl_exec( $ch );

        //------------------------------
        // Error? Display it!
        //------------------------------

        if ( curl_errno( $ch ) )
        {
            echo 'GCM error: ' . curl_error( $ch );
        }

        //------------------------------
        // Close curl handle
        //------------------------------

        curl_close( $ch );

        //------------------------------
        // Debug GCM response
        //------------------------------

        echo $result;
    }
}
?>