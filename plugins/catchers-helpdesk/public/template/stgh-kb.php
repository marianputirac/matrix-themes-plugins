<?php

use StgHelpdesk\Helpers\Stg_Helper_Saved_Replies;

if(!isset($is_settings)) {
	wp_enqueue_style( 'jqueryui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css', false, null );
}else{
	wp_enqueue_script( 'jquery-touch-punch');
}

$colCount = 3;
$rounded = true;

$artHeaderColor='#ff0000';
$artFontHeaderColor='#ffffff';

$artColor='#0000ff';
$artFontColor='#ffffff';

$searchButtonColor='#000000';
$searchButtonFontColor='#ffffff';

if(function_exists('get_term_meta')) {
	$args = array('meta_query' => array(
		'relation' => 'AND',
		array(
			'key' => 'stgh_term_meta',
			'value' => 'custom_field_toqa_meta',
			'compare' => 'LIKE'
		),
		array(
			'key'       => 'stgh_term_meta',
			'compare'   => 'EXISTS'
		)

	));

	$savedReplies = Stg_Helper_Saved_Replies::getSavedRepliesKBAll($args);
}else{
	$savedReplies = Stg_Helper_Saved_Replies::getSavedRepliesKBAllOld();
}

$singleArticles = $articles = array();
$hashes = array();
foreach($savedReplies as $savedReply)
{
	$tmp = array(
		'id' => $savedReply->term_id,
		'name' => $savedReply->name,
		'desc' => $savedReply->description,
        'topic' => $savedReply->meta_value['custom_field_topic_meta'],
		);

	$hash = md5($savedReply->meta_value['custom_field_topic_meta']);
	$articles[$hash][] = $tmp;
	$singleArticles[$savedReply->term_id] = $tmp;
	$hashes[$hash] = $savedReply->meta_value['custom_field_topic_meta'];
}


$articleId = (isset($_REQUEST['kb']))? $_REQUEST['kb']:false;

$kbUrl = getKbPage();
$kbRange = stgh_get_option('stgh_kbrange');
$newRange = array_keys( $hashes );

if(empty($kbRange)) {
	$kbRange = array_keys( $hashes );
}
else
{
	$kbRange = array_merge(array_intersect($kbRange,$newRange),array_diff($newRange,$kbRange));
}


if($articleId):
	$singleArticle = $singleArticles[$articleId];
    $topic = $singleArticle['topic'];
?>
    <div class="stgh-breadcrumb">
        <span><a href="<?=$kbUrl ?>"><?=__('Knowledge base', STG_HELPDESK_TEXT_DOMAIN_NAME)?></a></span>
        /
        <span><?=$topic ?></span>
    </div>

    <div class="stgh-signle-kb-article">
        <div class="stgh-signle-kb-article-content">
            <h2><?=$singleArticle['name']?></h2>
            <?php
                echo $singleArticle['desc'];
            ?>
        </div>
    </div>
<?php else:?>

<div id="stgh-kb-layout">
	<div id="stgh-kb-search">
		<div id="stgh-kb-search-title"></div>
		<div id="stgh-kb-search-form">
			<form action="" method="get">
				<input placeholder="<?= __('Search',STG_HELPDESK_TEXT_DOMAIN_NAME);?>" type="text" id="stgh-kb-search-s" name="stgh-kb-search-s">
			</form>
		</div>
	</div>
	<div id="stgh-kb-articles">
		<?php
		foreach($kbRange as $curHash):
            $topic =  $hashes[$curHash];
		    $tArticles = $articles[$curHash];
		?>
		<div class="stgh-kb-topic" id="<?php echo md5($topic);?>">
			<h3><?=$topic?></h3>
			<?php
				foreach($tArticles as $article):
					$url = add_query_arg('kb',$article['id'],$kbUrl);
			?>
					<div class="stgh-kb-article">
						<a href="<?=$url?>">
							<?=$article['name']?>
						</a>
					</div>
                <?php
			    endforeach;
                ?>
		</div>
		<?php
		endforeach;
		?>
	</div>
</div>
<?php endif;?>