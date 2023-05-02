<?php
function epn_email_content(){
    include( 'includes\epn-data-loader.php' );
    $news = array(
        'post_type'=>'news',
        'post_status'=>'publish',
        'date_query'    => array(
            'column'  => 'post_date',
            'after'   => '- 1 days'
        )
    );
    $data = array();
    $query = new WP_query($news);
    $message = '';
    if($query->have_posts()){
        while($query->have_posts()){
            $query->the_post();
            $url = get_the_permalink();
            $response = wp_remote_retrieve_body(wp_remote_get($url));
            $meta_description = epn_meta_description($response);
            $meta_title = epn_meta_title($response);
            $speed_score = epn_page_speed_score($url);
            $post = array(
                'title' => get_the_title(),
                'url' => $url,
                'meta_description' => $meta_description,
                'meta_title' => $meta_title,
                'page_speed_score' => $speed_score
            );
            array_push($data,$post);
        }
        foreach ($data as $post_data) {
            $message .= 'Title: ' . $post_data['title'] . "\n";
            $message .= 'URL: ' . $post_data['url'] . "\n";
            $message .= 'Meta Title: ' . $post_data['meta_title'] . "\n";
            $message .= 'Meta Description: ' . $post_data['meta_description'] . "\n";
            $message .= 'Page Speed Score: ' . $post_data['page_speed_score'];
            is_float($post_data['page_speed_score'])? $message .= " seconds" : $message .= "";
            $message .= "\n\n";
        }
    }
    else{
        $message .= "<h1>no recent posts today</h1>";
    }
    $to = get_option('admin_email');
    $subject = 'Daily Post Summary';
    $headers = '';
    wp_mail($to, $subject, $message, $headers);
}
