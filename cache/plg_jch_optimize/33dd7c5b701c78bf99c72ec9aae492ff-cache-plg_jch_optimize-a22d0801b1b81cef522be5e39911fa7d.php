<?php die("Access Denied"); ?>#x#a:2:{s:6:"output";s:0:"";s:6:"result";s:104034:"@CHARSET "UTF-8";
/**
 * SqueezeBox - Expandable Lightbox
 *
 * Allows to open various content as modal,
 * centered and animated box.
 *
 * @version		1.2
 *
 * @license		MIT-style license
 * @author		Harald Kirschner <mail [at] digitarald.de>
 * @author		Rouven Weßling <me [at] rouvenwessling.de>
 * @copyright	Author
 */

#sbox-overlay {
	position: absolute;
	background-color: #000;
	left: 0px;
	top: 0px;
}

#sbox-window {
	position: absolute;
	background-color: #fff;
	text-align: left;
	overflow: visible;
	padding: 10px;
	/* invalid values, but looks smoother! */
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	border-radius: 3px;
}

#sbox-btn-close {
	position: absolute;
	width: 30px;
	height: 30px;
	right: -15px;
	top: -15px;
	background: url(/media/system/images/modal/closebox.png) no-repeat center;
	border: none;
}

.sbox-window-ie6 #sbox-btn-close {
	background-image: url(/media/system/images/modal/closebox.gif);
}

.sbox-loading #sbox-content {
	background-image: url(/media/system/images/modal/spinner.gif);
	background-repeat: no-repeat;
	background-position: center;
}

#sbox-content {
	clear: both;
	overflow: auto;
	background-color: #fff;
	height: 100%;
	width: 100%;
}

.sbox-content-image#sbox-content {
	overflow: visible;
}

#sbox-image {
	display: block;
}

.sbox-content-image img {
	display: block;
	width: 100%;
	height: 100%;
}

.sbox-content-iframe#sbox-content {
	overflow: visible;
}

/* Hides flash (Firefox problem) and selects (IE) */
.body-overlayed embed, .body-overlayed object, .body-overlayed select {
	visibility: hidden;
}
#sbox-window embed, #sbox-window object, #sbox-window select {
	visibility: visible;
}

/* Shadows */
#sbox-window.shadow {
	-webkit-box-shadow: 0 0 10px rgba(0, 0, 0, 0.7);
	-moz-box-shadow: 0 0 10px rgba(0, 0, 0, 0.7);
	box-shadow: 0 0 10px rgba(0, 0, 0, 0.7);
}

.sbox-bg {
	position: absolute;
	width: 33px;
	height: 40px;
}

.sbox-bg-n {
	left: 0;
	top: -40px;
	width: 100%;
	background: url(/media/system/images/modal/bg_n.png) repeat-x;
}
.sbox-bg-ne {
	right: -33px;
	top: -40px;
	background: url(/media/system/images/modal/bg_ne.png) no-repeat;
}
.sbox-bg-e {
	right: -33px;
	top: 0;
	height: 100%;
	background: url(/media/system/images/modal/bg_e.png) repeat-y;
}
.sbox-bg-se {
	right: -33px;
	bottom: -40px;
	background: url(/media/system/images/modal/bg_se.png) no-repeat;
}
.sbox-bg-s {
	left: 0;
	bottom: -40px;
	width: 100%;
	background: url(/media/system/images/modal/bg_s.png) repeat-x;
}
.sbox-bg-sw {
	left: -33px;
	bottom: -40px;
	background: url(/media/system/images/modal/bg_sw.png) no-repeat;
}
.sbox-bg-w {
	left: -33px;
	top: 0;
	height: 100%;
	background: url(/media/system/images/modal/bg_w.png) repeat-y;
}
.sbox-bg-nw {
	left: -33px;
	top: -40px;
	background: url(/media/system/images/modal/bg_nw.png) no-repeat;
}

/**
 * @version		$Id: k2.css 1344 2011-11-25 16:47:03Z joomlaworks $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2011 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

/*
### Legend ###
Font families used:
	font-family:Georgia, "Times New Roman", Times, serif;
	font-family:"Trebuchet MS",Trebuchet,Arial,Verdana,Sans-serif;
	font-family:Arial, Helvetica, sans-serif;

Colors used:
	#f7fafe (light blue) used as background on all toolbars, category and user/author boxes

*/



/*----------------------------------------------------------------------
	Common Elements
----------------------------------------------------------------------*/

/* --- Basic typography --- */
a:active,
a:focus {outline:0;}
img {border:none;}

/* --- Global K2 container --- */
#k2Container {padding:0 0 24px 0;}
body.contentpane #k2Container {padding:16px;} /* used in popups */

/* --- General padding --- */
.k2Padding {padding:4px;}

/* --- Clearing --- */
.clr {clear:both;height:0;line-height:0;display:block;float:none;padding:0;margin:0;border:none;}

/* --- Zebra rows --- */
.even {background:#fffff0;padding:2px;border-bottom:1px dotted #ccc;}
.odd {background:#fff;padding:2px;border-bottom:1px dotted #ccc;}

/* --- RSS feed icon --- */
div.k2FeedIcon {padding:4px 8px;}
div.k2FeedIcon a,
div.k2FeedIcon a:hover {display:block;float:right;margin:0;padding:0;width:16px;height:16px;background:url(/components/com_k2/images/fugue/feed.png) no-repeat 50% 50%;}
div.k2FeedIcon a span,
div.k2FeedIcon a:hover span {display:none;}

/* --- Rating --- */
.itemRatingForm {display:block;vertical-align:middle;line-height:25px;float:left;}
.itemRatingLog {font-size:11px;margin:0;padding:0 0 0 4px;float:left;}
div.itemRatingForm .formLogLoading {background:url(/components/com_k2/images/system/loading.gif) no-repeat left center;height:25px;padding:0 0 0 20px;}
.itemRatingList,
.itemRatingList a:hover,
.itemRatingList .itemCurrentRating {background:transparent url(/components/com_k2/images/system/transparent_star.gif) left -1000px repeat-x;}
.itemRatingList {position:relative;float:left;width:125px;height:25px;overflow:hidden;list-style:none;margin:0;padding:0;background-position:left top;}
.itemRatingList li {display:inline;background:none;padding:0;}
.itemRatingList a,
.itemRatingList .itemCurrentRating {position:absolute;top:0;left:0;text-indent:-1000px;height:25px;line-height:25px;outline:none;overflow:hidden;border:none;cursor:pointer;}
.itemRatingList a:hover {background-position:left bottom;}
.itemRatingList a.one-star {width:20%;z-index:6;}
.itemRatingList a.two-stars {width:40%;z-index:5;}
.itemRatingList a.three-stars {width:60%;z-index:4;}
.itemRatingList a.four-stars {width:80%;z-index:3;}
.itemRatingList a.five-stars {width:100%;z-index:2;}
.itemRatingList .itemCurrentRating {z-index:1;background-position:0 center;margin:0;padding:0;}
span.siteRoot {display:none;}

/* --- CSS added with Javascript --- */
.smallerFontSize {font-size:100%;line-height:inherit;}
.largerFontSize {font-size:150%;line-height:140%;}

/* --- ReCaptcha --- */
.recaptchatable .recaptcha_image_cell,
#recaptcha_table {background-color:#fff !important;}
#recaptcha_table {border-color: #ccc !important;}
#recaptcha_response_field {border-color: #ccc !important;background-color:#fff !important;}

/* --- Primary lists in modules --- */
div.k2LatestCommentsBlock ul,
div.k2TopCommentersBlock ul,
div.k2ItemsBlock ul,
div.k2LoginBlock ul,
div.k2UserBlock ul.k2UserBlockActions,
div.k2UserBlock ul.k2UserBlockRenderedMenu,
div.k2ArchivesBlock ul,
div.k2AuthorsListBlock ul,
div.k2CategoriesListBlock ul,
div.k2UsersBlock ul {} /* Example CSS: padding:0;margin:0;list-style:none;*/

div.k2LatestCommentsBlock ul li,
div.k2TopCommentersBlock ul li,
div.k2ItemsBlock ul li,
div.k2LoginBlock ul li,
div.k2UserBlock ul.k2UserBlockActions li,
div.k2UserBlock ul.k2UserBlockRenderedMenu li,
div.k2ArchivesBlock ul li,
div.k2AuthorsListBlock ul li,
div.k2CategoriesListBlock ul li,
div.k2UsersBlock ul li {} /* Example CSS: display:block;clear:both;padding:2px 0;border-bottom:1px dotted #ccc;*/

.clearList {display:none;float:none;clear:both;} /* this class is used to clear all previous floating list elements */
.lastItem {border:none;} /* class appended on last list item */

/* --- Avatars --- */
.k2Avatar img {display:block;float:left;background:#fff;border:1px solid #ccc;padding:2px;margin:2px 4px 4px 0;}

/* --- Read more --- */
a.k2ReadMore {}
a.k2ReadMore:hover {}

/* --- Pagination --- */
div.k2Pagination {padding:8px;margin:24px 0 4px 0;text-align:center;color:#999;}

/* --- Pagination (additional styling for Joomla! 1.6+) --- */
div.k2Pagination ul {text-align:center;}
div.k2Pagination ul li {display:inline;}

/* --- Extra fields: CSV data styling --- */
table.csvData {}
table.csvData tr th {}
table.csvData tr td {}

/* --- Featured flags: the classes are repeated further below to indicate placement in the CSS structure --- */
div.itemIsFeatured,
div.catItemIsFeatured,
div.userItemIsFeatured {background:url(/components/com_k2/images/system/featuredItem.png) no-repeat 100% 0;}



/*----------------------------------------------------------------------
	Component: Item view
----------------------------------------------------------------------*/
a.itemPrintThisPage {display:block;width:160px;margin:4px auto 16px;padding:4px;background:#F7FAFE;border:1px solid #ccc;text-align:center;color:#555;font-size:13px;}
a.itemPrintThisPage:hover {background:#eee;text-decoration:none;}

div.itemView {padding:8px 0 24px 0;margin:0 0 24px 0;border-bottom:1px dotted #ccc;} /* this is the item container for this view */
div.itemIsFeatured {} /* Attach a class for each featured item */

span.itemEditLink {float:right;display:block;padding:4px 0;margin:0;width:120px;text-align:right;}
span.itemEditLink a {padding:2px 12px;border:1px solid #ccc;background:#eee;text-decoration:none;font-size:11px;font-weight:normal;font-family:Arial, Helvetica, sans-serif;}
span.itemEditLink a:hover {background:#ffffcc;}

div.itemHeader {}
	div.itemHeader span.itemDateCreated {color:#999;font-size:11px;}
	div.itemHeader h2.itemTitle {font-family:Georgia, "Times New Roman", Times, serif;font-size:36px;font-weight:normal;line-height:110%;padding:10px 0 4px 0;margin:0;}
	div.itemHeader h2.itemTitle span {}
	div.itemHeader h2.itemTitle span sup {font-size:12px;color:#CF1919;text-decoration:none;} /* "Featured" presented in superscript */
	div.itemHeader span.itemAuthor {display:block;padding:0;margin:0;}
	div.itemHeader span.itemAuthor a {}
	div.itemHeader span.itemAuthor a:hover {}

div.itemToolbar {padding:2px 0;margin:16px 0 0 0;border-top:1px dotted #ccc;border-bottom:1px dotted #ccc;background:#f7fafe;}
	div.itemToolbar ul {text-align:right;list-style:none;padding:0;margin:0;}
	div.itemToolbar ul li {display:inline;list-style:none;padding:0 4px 0 8px;margin:0;border-left:1px solid #ccc;text-align:center;background:none;font-size:12px;}
	div.itemToolbar ul > li:first-child {border:none;} /* remove the first CSS border from the left of the toolbar */
	div.itemToolbar ul li a {font-size:12px;font-weight:normal;}
	div.itemToolbar ul li a:hover {}
	div.itemToolbar ul li a span {}
	div.itemToolbar ul li a.itemPrintLink {}
	div.itemToolbar ul li a.itemPrintLink span {}
	div.itemToolbar ul li a.itemEmailLink {}
	div.itemToolbar ul li a.itemEmailLink span {}
	div.itemToolbar ul li a.itemVideoLink {}
	div.itemToolbar ul li a.itemVideoLink span {}
	div.itemToolbar ul li a.itemImageGalleryLink {}
	div.itemToolbar ul li a.itemImageGalleryLink span {}
	div.itemToolbar ul li a.itemCommentsLink {}
	div.itemToolbar ul li a.itemCommentsLink span {}
	div.itemToolbar ul li a img {vertical-align:middle;}
	div.itemToolbar ul li span.itemTextResizerTitle {}
	div.itemToolbar ul li a#fontDecrease {margin:0 0 0 2px;}
	div.itemToolbar ul li a#fontDecrease img {width:13px;height:13px;background:url(/components/com_k2/images/system/font_decrease.gif) no-repeat;}
	div.itemToolbar ul li a#fontIncrease {margin:0 0 0 2px;}
	div.itemToolbar ul li a#fontIncrease img {width:13px;height:13px;background:url(/components/com_k2/images/system/font_increase.gif) no-repeat;}
	div.itemToolbar ul li a#fontDecrease span,
	div.itemToolbar ul li a#fontIncrease span {display:none;}

div.itemRatingBlock {padding:8px 0;}
	div.itemRatingBlock span {display:block;float:left;font-style:normal;padding:0 4px 0 0;margin:0;color:#999;}

div.itemBody {padding:8px 0;margin:0;}

div.itemImageBlock {padding:8px;margin:0 0 16px 0;}
	span.itemImage {display:block;text-align:center;margin:0 0 8px 0;}
	span.itemImage img {border:1px solid #ccc;padding:8px;}
	span.itemImageCaption {color:#666;float:left;display:block;font-size:11px;}
	span.itemImageCredits {color:#999;float:right;display:block;font-style:italic;font-size:11px;}

div.itemIntroText {color:#444;font-size:18px;font-weight:bold;line-height:24px;padding:4px 0 12px 0;}
	div.itemIntroText img {}

div.itemFullText {}
	div.itemFullText h3 {margin:0;padding:16px 0 4px 0;}
	div.itemFullText p {}
	div.itemFullText img {}

div.itemExtraFields {margin:16px 0 0 0;padding:8px 0 0 0;border-top:1px dotted #ddd;}
	div.itemExtraFields h3 {margin:0;padding:0 0 8px 0;line-height:normal !important;}
	div.itemExtraFields ul {margin:0;padding:0;list-style:none;}
	div.itemExtraFields ul li {display:block;}
	div.itemExtraFields ul li span.itemExtraFieldsLabel {display:block;float:left;font-weight:bold;margin:0 4px 0 0;width:30%;}
	div.itemExtraFields ul li span.itemExtraFieldsValue {}

div.itemContentFooter {display:block;text-align:right;padding:4px;margin:16px 0 4px 0;border-top:1px solid #ddd;color:#999;}
	span.itemHits {float:left;}
	span.itemDateModified {}
	
div.itemSocialSharing {padding:8px 0;}
	div.itemTwitterButton {float:left;margin:2px 24px 0 0;}
	div.itemFacebookButton {float:left;margin-right:24px;width:200px;}
	div.itemGooglePlusOneButton {}
	
div.itemLinks {margin:16px 0;padding:0;}

div.itemCategory {padding:4px;border-bottom:1px dotted #ccc;}
	div.itemCategory span {font-weight:bold;color:#555;padding:0 4px 0 0;}
	div.itemCategory a {}
div.itemTagsBlock {padding:4px;border-bottom:1px dotted #ccc;}
	div.itemTagsBlock span {font-weight:bold;color:#555;padding:0 4px 0 0;}
	div.itemTagsBlock ul.itemTags {list-style:none;padding:0;margin:0;display:inline;}
	div.itemTagsBlock ul.itemTags li {display:inline;list-style:none;padding:0 4px 0 0;margin:0;text-align:center;}
	div.itemTagsBlock ul.itemTags li a {}
	div.itemTagsBlock ul.itemTags li a:hover {}

div.itemAttachmentsBlock {padding:4px;border-bottom:1px dotted #ccc;}
	div.itemAttachmentsBlock span {font-weight:bold;color:#555;padding:0 4px 0 0;}
	div.itemAttachmentsBlock ul.itemAttachments {list-style:none;padding:0;margin:0;display:inline;}
	div.itemAttachmentsBlock ul.itemAttachments li {display:inline;list-style:none;padding:0 4px;margin:0;text-align:center;}
	div.itemAttachmentsBlock ul.itemAttachments li a {}
	div.itemAttachmentsBlock ul.itemAttachments li a:hover {}
	div.itemAttachmentsBlock ul.itemAttachments li span {font-size:10px;color:#999;font-weight:normal;}

/* Author block */
div.itemAuthorBlock {background:#f7fafe;border:1px solid #ddd;margin:0 0 16px 0;padding:8px;}
	div.itemAuthorBlock img.itemAuthorAvatar {float:left;display:block;background:#fff;padding:4px;border:1px solid #ddd;margin:0 8px 0 0;}
	div.itemAuthorBlock div.itemAuthorDetails {margin:0;padding:4px 0 0 0;}
	div.itemAuthorBlock div.itemAuthorDetails h3.authorName {margin:0 0 4px 0;padding:0;}
	div.itemAuthorBlock div.itemAuthorDetails h3.authorName a {font-family:Georgia, "Times New Roman", Times, serif;font-size:16px;}
	div.itemAuthorBlock div.itemAuthorDetails h3.authorName a:hover {}
	div.itemAuthorBlock div.itemAuthorDetails p {}
	div.itemAuthorBlock div.itemAuthorDetails span.itemAuthorUrl {font-weight:bold;color:#555;border-right:1px solid #ccc;padding:0 8px 0 0;margin:0 4px 0 0;}
	div.itemAuthorBlock div.itemAuthorDetails span.itemAuthorEmail {font-weight:bold;color:#555;}
	div.itemAuthorBlock div.itemAuthorDetails span.itemAuthorUrl a,
	div.itemAuthorBlock div.itemAuthorDetails span.itemAuthorEmail a {font-weight:normal;}

/* Author latest */
div.itemAuthorLatest {margin-bottom:16px;padding:0;}
	div.itemAuthorLatest h3 {}
	div.itemAuthorLatest ul {}
		div.itemAuthorLatest ul li {}
			div.itemAuthorLatest ul li a {}
			div.itemAuthorLatest ul li a:hover {}

/* Related by tag */
div.itemRelated {margin-bottom:16px;padding:0;} /* Add overflow-x:scroll; if you want to enable the scrolling features, as explained in item.php */
	div.itemRelated h3 {}
	div.itemRelated ul {}
		div.itemRelated ul li {}
		div.itemRelated ul li.k2ScrollerElement {float:left;overflow:hidden;border:1px solid #ccc;padding:4px;margin:0 4px 4px 0;background:#fff;} /* you need to insert this class in the related li element in item.php for this styling to take effect */
		div.itemRelated ul li.clr {clear:both;height:0;line-height:0;display:block;float:none;padding:0;margin:0;border:none;}
			a.itemRelTitle {}
			div.itemRelCat {}
				div.itemRelCat a {}
			div.itemRelAuthor {}
				div.itemRelAuthor a {}
			img.itemRelImg {}
			div.itemRelIntrotext {}
			div.itemRelFulltext {}
			div.itemRelMedia {}
			div.itemRelImageGallery {}

/* Video */
div.itemVideoBlock {margin:0 0 16px 0;padding:16px;background:#010101 url(/components/com_k2/images/system/videoplayer-bg.gif) repeat-x bottom;}
	div.itemVideoBlock div.itemVideoEmbedded {text-align:center;} /* for embedded videos (not using AllVideos) */
	div.itemVideoBlock span.itemVideo {display:block;overflow:hidden;}
	div.itemVideoBlock span.itemVideoCaption {color:#eee;float:left;display:block;font-size:11px;font-weight:bold;width:60%;}
	div.itemVideoBlock span.itemVideoCredits {color:#eee;float:right;display:block;font-style:italic;font-size:11px;width:35%;text-align:right;}

/* Image Gallery */
div.itemImageGallery {margin:0 0 16px 0;padding:0;}

/* Article navigation */
div.itemNavigation {padding:4px 8px;margin:0 0 24px 0;border-top:1px dotted #ccc;border-bottom:1px dotted #ccc;background:#fffff0;}
	div.itemNavigation span.itemNavigationTitle {color:#999;}
	div.itemNavigation a.itemPrevious {padding:0 12px;}
	div.itemNavigation a.itemNext {padding:0 12px;}

/* Comments */
div.itemComments {background:#f7fafe;border:1px solid #ddd;padding:16px;}

	div.itemComments ul.itemCommentsList {margin:0 0 16px;padding:0;list-style:none;}
	div.itemComments ul.itemCommentsList li {padding:4px;margin:0;border-bottom:1px dotted #ddd;}
	div.itemComments ul.itemCommentsList li.authorResponse {background:url(/components/com_k2/images/system/stripes.png) repeat;}
	div.itemComments ul.itemCommentsList li.unpublishedComment {background:#ffeaea;}
	div.itemComments ul.itemCommentsList li img {float:left;margin:4px 4px 4px 0;padding:4px;background:#fff;border-bottom:1px solid #d7d7d7;border-left:1px solid #f2f2f2;border-right:1px solid #f2f2f2;}
	div.itemComments ul.itemCommentsList li span.commentDate {padding:0 4px 0 0;margin:0 8px 0 0;border-right:1px solid #ccc;font-weight:bold;font-size:14px;}
	div.itemComments ul.itemCommentsList li span.commentAuthorName {font-weight:bold;font-size:14px;}
	div.itemComments ul.itemCommentsList li p {padding:4px 0;}
	div.itemComments ul.itemCommentsList li span.commentAuthorEmail {display:none;}
	div.itemComments ul.itemCommentsList li span.commentLink {float:right;margin-left:8px;}
	div.itemComments ul.itemCommentsList li span.commentLink a {font-size:11px;color:#999;text-decoration:underline;}
	div.itemComments ul.itemCommentsList li span.commentLink a:hover {font-size:11px;color:#555;text-decoration:underline;}
	
	div.itemComments ul.itemCommentsList li span.commentToolbar {display:block;clear:both;}
	div.itemComments ul.itemCommentsList li span.commentToolbar a {font-size:11px;color:#999;text-decoration:underline;margin-right:4px;}
	div.itemComments ul.itemCommentsList li span.commentToolbar a:hover {font-size:11px;color:#555;text-decoration:underline;}
	div.itemComments ul.itemCommentsList li span.commentToolbarLoading {background:url(/components/com_k2/images/system/searchLoader.gif) no-repeat 100% 50%;}

div.itemCommentsPagination {padding:4px;margin:0 0 24px 0;}
	div.itemCommentsPagination span.pagination {display:block;float:right;clear:both;}

div.itemCommentsForm h3 {margin:0;padding:0 0 4px 0;}
	div.itemCommentsForm p.itemCommentsFormNotes {border-top:2px solid #ccc;}
	div.itemCommentsForm form {}
	div.itemCommentsForm form label.formComment {display:block;margin:12px 0 0 2px;}
	div.itemCommentsForm form label.formName {display:block;margin:12px 0 0 2px;}
	div.itemCommentsForm form label.formEmail {display:block;margin:12px 0 0 2px;}
	div.itemCommentsForm form label.formUrl {display:block;margin:12px 0 0 2px;}
	div.itemCommentsForm form label.formRecaptcha {display:block;margin:12px 0 0 2px;}
	div.itemCommentsForm form textarea.inputbox {display:block;width:350px;height:160px;margin:0;}
	div.itemCommentsForm form input.inputbox {display:block;width:350px;margin:0;}
	div.itemCommentsForm form input#submitCommentButton {display:block;margin:16px 0 0 0;padding:4px;border:1px solid #ccc;background:#eee;font-size:16px;}
	div.itemCommentsForm form span#formLog {margin:0 0 0 20px;padding:0 0 0 20px;font-weight:bold;color:#CF1919;}
	div.itemCommentsForm form .formLogLoading {background:url(/components/com_k2/images/system/loading.gif) no-repeat left center;}

/* "Report comment" form */
div.k2ReportCommentFormContainer {padding:8px;width:480px;margin:0 auto;}
	div.k2ReportCommentFormContainer blockquote {width:462px;background:#f7fafe;border:1px solid #ddd;padding:8px;margin:0 0 8px 0;}
	div.k2ReportCommentFormContainer blockquote span.quoteIconLeft {font-style:italic;font-weight:bold;font-size:40px;color:#135CAE;line-height:30px;vertical-align:top;display:block;float:left;}
	div.k2ReportCommentFormContainer blockquote span.quoteIconRight {font-style:italic;font-weight:bold;font-size:40px;color:#135CAE;line-height:30px;vertical-align:top;display:block;float:right;}
	div.k2ReportCommentFormContainer blockquote span.theComment {font-family:Georgia, "Times New Roman", Times, serif;font-style:italic;font-size:12px;font-weight:normal;color:#000;padding:0 4px;}
	div.k2ReportCommentFormContainer form label {display:block;font-weight:bold;}
	div.k2ReportCommentFormContainer form input,
	div.k2ReportCommentFormContainer form textarea {display:block;border:1px solid #ddd;font-size:12px;padding:2px;margin:0 0 8px 0;width:474px;}
	div.k2ReportCommentFormContainer form #recaptcha {margin-bottom:24px;}
	div.k2ReportCommentFormContainer form span#formLog {margin:0 0 0 20px;padding:0 0 0 20px;font-weight:bold;color:#CF1919;}
	div.k2ReportCommentFormContainer form .formLogLoading {background:url(/components/com_k2/images/system/loading.gif) no-repeat left center;}

/* Back to top link */
div.itemBackToTop {text-align:right;}
	div.itemBackToTop a {text-decoration:underline;}
	div.itemBackToTop a:hover {text-decoration:underline;}



/*----------------------------------------------------------------------
	Component: Itemlist view (category)
----------------------------------------------------------------------*/

div.itemListCategoriesBlock {}

/* --- Category block --- */
div.itemListCategory {background:#f7fafe;border:1px solid #ddd;margin:4px 0;padding:8px;}
	span.catItemAddLink {display:block;padding:8px 0;margin:0 0 4px 0;border-bottom:1px dotted #ccc;text-align:right;}
	span.catItemAddLink a {padding:4px 16px;border:1px solid #ccc;background:#eee;text-decoration:none;}
	span.catItemAddLink a:hover {background:#ffffcc;}
	div.itemListCategory img {float:left;display:block;background:#fff;padding:4px;border:1px solid #ddd;margin:0 8px 0 0;}
	div.itemListCategory h2 {}
	div.itemListCategory p {}

/* --- Sub-category block --- */
div.itemListSubCategories {}
	div.itemListSubCategories h3 {}
		div.subCategoryContainer {float:left;}
		div.subCategoryContainerLast {} /* this class is appended to the last container on each row of items (useful when you want to set 0 padding/margin to the last container) */
			div.subCategory {background:#f7fafe;border:1px solid #ddd;margin:4px;padding:8px;}
				div.subCategory a.subCategoryImage,
				div.subCategory a.subCategoryImage:hover {text-align:center;display:block;}
				div.subCategory a.subCategoryImage img,
				div.subCategory a.subCategoryImage:hover img {background:#fff;padding:4px;border:1px solid #ddd;margin:0 8px 0 0;}
				div.subCategory h2 {}
				div.subCategory h2 a {}
				div.subCategory h2 a:hover {}
				div.subCategory p {}

/* --- Item groups --- */
div.itemList {}
	div#itemListLeading {}
	div#itemListPrimary {}
	div#itemListSecondary {}
	div#itemListLinks {background:#f7fafe;border:1px solid #ddd;margin:8px 0;padding:8px;}

		div.itemContainer {float:left;}
		div.itemContainerLast {} /* this class is appended to the last container on each row of items (useful when you want to set 0 padding/margin to the last container) */

/* --- Item block for each item group --- */
div.catItemView {padding:4px;} /* this is the item container for this view - we add a generic padding so that items don't get stuck with each other */

	/* Additional class appended to the element above for further styling per group item */
	div.groupLeading {}
	div.groupPrimary {}
	div.groupSecondary {}
	div.groupLinks {padding:0;margin:0;}

	div.catItemIsFeatured {} /* Attach a class for each featured item */

span.catItemEditLink {float:right;display:block;padding:4px 0;margin:0;width:120px;text-align:right;}
span.catItemEditLink a {padding:2px 12px;border:1px solid #ccc;background:#eee;text-decoration:none;font-size:11px;font-weight:normal;font-family:Arial, Helvetica, sans-serif;}
span.catItemEditLink a:hover {background:#ffffcc;}

div.catItemHeader {}
	div.catItemHeader span.catItemDateCreated {color:#999;font-size:11px;}
	div.catItemHeader h3.catItemTitle {font-family:Georgia, "Times New Roman", Times, serif;font-size:24px;font-weight:normal;line-height:110%;padding:10px 0 4px 0;margin:0;}
	div.catItemHeader h3.catItemTitle span {}
	div.catItemHeader h3.catItemTitle span sup {font-size:12px;color:#CF1919;text-decoration:none;} /* superscript */
	div.catItemHeader span.catItemAuthor {display:block;padding:0;margin:0;}
	div.catItemHeader span.catItemAuthor a {}
	div.catItemHeader span.catItemAuthor a:hover {}

div.catItemRatingBlock {padding:8px 0;}
	div.catItemRatingBlock span {display:block;float:left;font-style:normal;padding:0 4px 0 0;margin:0;color:#999;}

div.catItemBody {padding:8px 0;margin:0;}

div.catItemImageBlock {padding:8px;margin:0 0 16px 0;}
	span.catItemImage {display:block;text-align:center;margin:0 0 8px 0;}
	span.catItemImage img {border:1px solid #ccc;padding:8px;}

div.catItemIntroText {font-size:inherit;font-weight:normal;line-height:inherit;padding:4px 0 12px 0;}
	div.catItemIntroText img {}

div.catItemExtraFields, div.genericItemExtraFields {margin:16px 0 0 0;padding:8px 0 0 0;border-top:1px dotted #ddd;}
	div.catItemExtraFields h4, div.genericItemExtraFields h4 {margin:0;padding:0 0 8px 0;line-height:normal !important;}
	div.catItemExtraFields ul, div.genericItemExtraFields ul {margin:0;padding:0;list-style:none;}
	div.catItemExtraFields ul li, div.genericItemExtraFields ul li {display:block;}
	div.catItemExtraFields ul li span.catItemExtraFieldsLabel, div.genericItemExtraFields ul li span.genericItemExtraFieldsLabel {display:block;float:left;font-weight:bold;margin:0 4px 0 0;width:30%;}
	div.catItemExtraFields ul li span.catItemExtraFieldsValue {}

div.catItemLinks {margin:0 0 16px 0;padding:0;}

div.catItemHitsBlock {padding:4px;border-bottom:1px dotted #ccc;}
	span.catItemHits {}

div.catItemCategory {padding:4px;border-bottom:1px dotted #ccc;}
	div.catItemCategory span {font-weight:bold;color:#555;padding:0 4px 0 0;}
	div.catItemCategory a {}

div.catItemTagsBlock {padding:4px;border-bottom:1px dotted #ccc;}
	div.catItemTagsBlock span {font-weight:bold;color:#555;padding:0 4px 0 0;}
	div.catItemTagsBlock ul.catItemTags {list-style:none;padding:0;margin:0;display:inline;}
	div.catItemTagsBlock ul.catItemTags li {display:inline;list-style:none;padding:0 4px 0 0;margin:0;text-align:center;}
	div.catItemTagsBlock ul.catItemTags li a {}
	div.catItemTagsBlock ul.catItemTags li a:hover {}

div.catItemAttachmentsBlock {padding:4px;border-bottom:1px dotted #ccc;}
	div.catItemAttachmentsBlock span {font-weight:bold;color:#555;padding:0 4px 0 0;}
	div.catItemAttachmentsBlock ul.catItemAttachments {list-style:none;padding:0;margin:0;display:inline;}
	div.catItemAttachmentsBlock ul.catItemAttachments li {display:inline;list-style:none;padding:0 4px;margin:0;text-align:center;}
	div.catItemAttachmentsBlock ul.catItemAttachments li a {}
	div.catItemAttachmentsBlock ul.catItemAttachments li a:hover {}
	div.catItemAttachmentsBlock ul.catItemAttachments li span {font-size:10px;color:#999;font-weight:normal;}

/* Video */
div.catItemVideoBlock {margin:0 0 16px 0;padding:16px;background:#010101 url(/components/com_k2/images/system/videoplayer-bg.gif) repeat-x bottom;}
	div.catItemVideoBlock div.catItemVideoEmbedded {text-align:center;} /* for embedded videos (not using AllVideos) */
	div.catItemVideoBlock span.catItemVideo {display:block;}

/* Image Gallery */
div.catItemImageGallery {margin:0 0 16px 0;padding:0;}

/* Anchor link to comments */
div.catItemCommentsLink {display:inline;margin:0 8px 0 0;padding:0 8px 0 0;border-right:1px solid #ccc;}
	div.catItemCommentsLink a {}
	div.catItemCommentsLink a:hover {}

/* Read more link */
div.catItemReadMore {display:inline;}
	div.catItemReadMore a {}
	div.catItemReadMore a:hover {}

/* Modified date */
span.catItemDateModified {display:block;text-align:right;padding:4px;margin:4px 0;color:#999;border-top:1px solid #ddd;}



/*----------------------------------------------------------------------
	Component: Itemlist view (user)
----------------------------------------------------------------------*/

/* User info block */
div.userView {}
	div.userBlock {background:#f7fafe;border:1px solid #ddd;margin:0 0 16px 0;padding:8px;clear:both;}

		span.userItemAddLink {display:block;padding:8px 0;margin:0 0 4px 0;border-bottom:1px dotted #ccc;text-align:right;}
		span.userItemAddLink a {padding:4px 16px;border:1px solid #ccc;background:#eee;text-decoration:none;}
		span.userItemAddLink a:hover {background:#ffffcc;}

		div.userBlock img {display:block;float:left;background:#fff;padding:4px;border:1px solid #ddd;margin:0 8px 0 0;}
		div.userBlock h2 {}
		div.userBlock div.userDescription {padding:4px 0;}
		div.userBlock div.userAdditionalInfo {padding:4px 0;margin:8px 0 0 0;}
			span.userURL {font-weight:bold;color:#555;display:block;}
			span.userEmail {font-weight:bold;color:#555;display:block;}

		div.userItemList {}

/* User items */
div.userItemView {} /* this is the item container for this view */
div.userItemIsFeatured {} /* Attach a class for each featured item */

div.userItemViewUnpublished {opacity:0.9;border:4px dashed #ccc;background:#fffff2;padding:8px;margin:8px 0;}

span.userItemEditLink {float:right;display:block;padding:4px 0;margin:0;width:120px;text-align:right;}
	span.userItemEditLink a {padding:2px 12px;border:1px solid #ccc;background:#eee;text-decoration:none;font-size:11px;font-weight:normal;font-family:Arial, Helvetica, sans-serif;}
	span.userItemEditLink a:hover {background:#ffffcc;}

div.userItemHeader {}
	div.userItemHeader span.userItemDateCreated {color:#999;font-size:11px;}
	div.userItemHeader h3.userItemTitle {font-family:Georgia, "Times New Roman", Times, serif;font-size:24px;font-weight:normal;line-height:110%;padding:10px 0 4px 0;margin:0;}
	div.userItemHeader h3.userItemTitle span sup {font-size:12px;color:#CF1919;text-decoration:none;} /* "Unpublished" presented in superscript */

div.userItemBody {padding:8px 0;margin:0;}

div.userItemImageBlock {padding:0;margin:0;float:left;}
	span.userItemImage {display:block;text-align:center;margin:0 8px 8px 0;}
	span.userItemImage img {border:1px solid #ccc;padding:8px;}

div.userItemIntroText {font-size:inherit;font-weight:normal;line-height:inherit;padding:4px 0 12px 0;}
	div.userItemIntroText img {}

div.userItemLinks {margin:0 0 16px 0;padding:0;}

div.userItemCategory {padding:4px;border-bottom:1px dotted #ccc;}
	div.userItemCategory span {font-weight:bold;color:#555;padding:0 4px 0 0;}
	div.userItemCategory a {}

div.userItemTagsBlock {padding:4px;border-bottom:1px dotted #ccc;}
	div.userItemTagsBlock span {font-weight:bold;color:#555;padding:0 4px 0 0;}
	div.userItemTagsBlock ul.userItemTags {list-style:none;padding:0;margin:0;display:inline;}
	div.userItemTagsBlock ul.userItemTags li {display:inline;list-style:none;padding:0 4px 0 0;margin:0;text-align:center;}
	div.userItemTagsBlock ul.userItemTags li a {}
	div.userItemTagsBlock ul.userItemTags li a:hover {}

/* Anchor link to comments */
div.userItemCommentsLink {display:inline;margin:0 8px 0 0;padding:0 8px 0 0;border-right:1px solid #ccc;}
	div.userItemCommentsLink a {}
	div.userItemCommentsLink a:hover {}

/* Read more link */
div.userItemReadMore {display:inline;}
	div.userItemReadMore a {}
	div.userItemReadMore a:hover {}



/*----------------------------------------------------------------------
	Component: Itemlist view (tag)
----------------------------------------------------------------------*/
div.tagView {}

div.tagItemList {}

div.tagItemView {border-bottom:1px dotted #ccc;padding:8px 0;margin:0 0 16px 0;} /* this is the item container for this view */

div.tagItemHeader {}
	div.tagItemHeader span.tagItemDateCreated {color:#999;font-size:11px;}
	div.tagItemHeader h2.tagItemTitle {font-family:Georgia, "Times New Roman", Times, serif;font-size:24px;font-weight:normal;line-height:110%;padding:10px 0 4px 0;margin:0;}

div.tagItemBody {padding:8px 0;margin:0;}

div.tagItemImageBlock {padding:0;margin:0;float:left;}
	span.tagItemImage {display:block;text-align:center;margin:0 8px 8px 0;}
	span.tagItemImage img {border:1px solid #ccc;padding:8px;}

div.tagItemIntroText {font-size:inherit;font-weight:normal;line-height:inherit;padding:4px 0 12px 0;}
	div.tagItemIntroText img {}
	
	div.tagItemExtraFields {}
		div.tagItemExtraFields h4 {}
		div.tagItemExtraFields ul {}
			div.tagItemExtraFields ul li {}
				div.tagItemExtraFields ul li span.tagItemExtraFieldsLabel {}
				div.tagItemExtraFields ul li span.tagItemExtraFieldsValue {}

	div.tagItemCategory {display:inline;margin:0 8px 0 0;padding:0 8px 0 0;border-right:1px solid #ccc;}
		div.tagItemCategory span {font-weight:bold;color:#555;padding:0 4px 0 0;}
		div.tagItemCategory a {}

/* Read more link */
div.tagItemReadMore {display:inline;}
	div.tagItemReadMore a {}
	div.tagItemReadMore a:hover {}



/*----------------------------------------------------------------------
	Component: Itemlist view (generic)
----------------------------------------------------------------------*/
div.genericView {}

div.genericItemList {}

div.genericItemView {border-bottom:1px dotted #ccc;padding:8px 0;margin:0 0 16px 0;} /* this is the item container for this view */

div.genericItemHeader {}
	div.genericItemHeader span.genericItemDateCreated {color:#999;font-size:11px;}
	div.genericItemHeader h2.genericItemTitle {font-family:Georgia, "Times New Roman", Times, serif;font-size:24px;font-weight:normal;line-height:110%;padding:10px 0 4px 0;margin:0;}

div.genericItemBody {padding:8px 0;margin:0;}

div.genericItemImageBlock {padding:0;margin:0;float:left;}
	span.genericItemImage {display:block;text-align:center;margin:0 8px 8px 0;}
	span.genericItemImage img {border:1px solid #ccc;padding:8px;}

div.genericItemIntroText {font-size:inherit;font-weight:normal;line-height:inherit;padding:4px 0 12px 0;}
	div.genericItemIntroText img {}
	
	div.genericItemExtraFields {}
		div.genericItemExtraFields h4 {}
		div.genericItemExtraFields ul {}
			div.genericItemExtraFields ul li {}
				div.genericItemExtraFields ul li span.genericItemExtraFieldsLabel {}
				div.genericItemExtraFields ul li span.genericItemExtraFieldsValue {}

	div.genericItemCategory {display:inline;margin:0 8px 0 0;padding:0 8px 0 0;border-right:1px solid #ccc;}
		div.genericItemCategory span {font-weight:bold;color:#555;padding:0 4px 0 0;}
		div.genericItemCategory a {}

/* Read more link */
div.genericItemReadMore {display:inline;}
	div.genericItemReadMore a {}
	div.genericItemReadMore a:hover {}

/* --- Google Search --- */
#k2Container div.gsc-branding-text {text-align:right;}
#k2Container div.gsc-control {width:100%;}
#k2Container div.gs-visibleUrl {display:none;}



/*----------------------------------------------------------------------
	Component: Latest view
----------------------------------------------------------------------*/

div.latestItemsContainer {float:left;}

/* Category info block */
div.latestItemsCategory {background:#f7fafe;border:1px solid #ddd;margin:0 8px 8px 0;padding:8px;}
	div.latestItemsCategoryImage {text-align:center;}
	div.latestItemsCategoryImage img {background:#fff;padding:4px;border:1px solid #ddd;margin:0 8px 0 0;}
div.latestItemsCategory h2 {}
div.latestItemsCategory p {}

/* User info block */
div.latestItemsUser {background:#f7fafe;border:1px solid #ddd;margin:0 8px 8px 0;padding:8px;clear:both;}
	div.latestItemsUser img {display:block;float:left;background:#fff;padding:4px;border:1px solid #ddd;margin:0 8px 0 0;}
	div.latestItemsUser h2 {}
	div.latestItemsUser p.latestItemsUserDescription {padding:4px 0;}
	div.latestItemsUser p.latestItemsUserAdditionalInfo {padding:4px 0;margin:8px 0 0 0;}
		span.latestItemsUserURL {font-weight:bold;color:#555;display:block;}
		span.latestItemsUserEmail {font-weight:bold;color:#555;display:block;}

/* Latest items list */
div.latestItemList {padding:0 8px 8px 0;}

div.latestItemView {} /* this is the item container for this view */

div.latestItemHeader {}
	div.latestItemHeader h3.latestItemTitle {font-family:Georgia, "Times New Roman", Times, serif;font-size:24px;font-weight:normal;line-height:110%;padding:10px 0 4px 0;margin:0;}

span.latestItemDateCreated {color:#999;font-size:11px;}

div.latestItemBody {padding:8px 0;margin:0;}

div.latestItemImageBlock {padding:0;margin:0;float:left;}
	span.latestItemImage {display:block;text-align:center;margin:0 8px 8px 0;}
	span.latestItemImage img {border:1px solid #ccc;padding:8px;}

div.latestItemIntroText {font-size:inherit;font-weight:normal;line-height:inherit;padding:4px 0 12px 0;}
	div.latestItemIntroText img {}

div.latestItemLinks {margin:0 0 16px 0;padding:0;}

div.latestItemCategory {padding:4px;border-bottom:1px dotted #ccc;}
	div.latestItemCategory span {font-weight:bold;color:#555;padding:0 4px 0 0;}
	div.latestItemCategory a {}

div.latestItemTagsBlock {padding:4px;border-bottom:1px dotted #ccc;}
	div.latestItemTagsBlock span {font-weight:bold;color:#555;padding:0 4px 0 0;}
	div.latestItemTagsBlock ul.latestItemTags {list-style:none;padding:0;margin:0;display:inline;}
	div.latestItemTagsBlock ul.latestItemTags li {display:inline;list-style:none;padding:0 4px 0 0;margin:0;text-align:center;}
	div.latestItemTagsBlock ul.latestItemTags li a {}
	div.latestItemTagsBlock ul.latestItemTags li a:hover {}

/* Video */
div.latestItemVideoBlock {margin:0 0 16px 0;padding:16px;background:#010101 url(/components/com_k2/images/system/videoplayer-bg.gif) repeat-x bottom;}
	div.latestItemVideoBlock span.latestItemVideo {display:block;}

/* Anchor link to comments */
div.latestItemCommentsLink {display:inline;margin:0 8px 0 0;padding:0 8px 0 0;border-right:1px solid #ccc;}
	div.latestItemCommentsLink a {}
	div.latestItemCommentsLink a:hover {}

/* Read more link */
div.latestItemReadMore {display:inline;}
	div.latestItemReadMore a {}
	div.latestItemReadMore a:hover {}

/* Items presented in a list */
h2.latestItemTitleList {font-size:14px;padding:2px 0;margin:8px 0 2px 0;font-family:Arial, Helvetica, sans-serif;border-bottom:1px dotted #ccc;line-height:normal;}



/*----------------------------------------------------------------------
	Component: Register & profile page views (register.php & profile.php)
----------------------------------------------------------------------*/
.k2AccountPage {}
.k2AccountPage table {}
.k2AccountPage table tr th {}
.k2AccountPage table tr td {}
.k2AccountPage table tr td label {white-space:nowrap;}
img.k2AccountPageImage {border:4px solid #ddd;margin:10px 0;padding:0;display:block;}
.k2AccountPage div.k2AccountPageNotice {padding:8px;}
.k2AccountPage div.k2AccountPageUpdate {border-top:1px dotted #ccc;margin:8px 0;padding:8px;text-align:right;}

.k2AccountPage th.k2ProfileHeading {text-align:left;font-size:18px;padding:8px;background:#f6f6f6;/*border-bottom:1px solid #e9e9e9;*/}
.k2AccountPage td#userAdminParams {padding:0;margin:0;}
.k2AccountPage table.admintable td.key,
.k2AccountPage table.admintable td.paramlist_key {background:#f6f6f6;border-bottom:1px solid #e9e9e9;border-right:1px solid #e9e9e9;color:#666;font-weight:bold;text-align:right;font-size:11px;width:140px;}

/* Profile edit */
.k2AccountPage table.admintable {}
.k2AccountPage table.admintable tr td {}
.k2AccountPage table.admintable tr td span {}
.k2AccountPage table.admintable tr td span label {}



/*----------------------------------------------------------------------
	Modules: mod_k2_comments
----------------------------------------------------------------------*/

/* Latest Comments */
div.k2LatestCommentsBlock {}
div.k2LatestCommentsBlock ul {}
div.k2LatestCommentsBlock ul li {}
div.k2LatestCommentsBlock ul li.lastItem {}
div.k2LatestCommentsBlock ul li a.lcAvatar img {}
div.k2LatestCommentsBlock ul li a {}
div.k2LatestCommentsBlock ul li a:hover {}
div.k2LatestCommentsBlock ul li span.lcComment {}
div.k2LatestCommentsBlock ul li span.lcUsername {}
div.k2LatestCommentsBlock ul li span.lcCommentDate {color:#999;}
div.k2LatestCommentsBlock ul li span.lcItemTitle {}
div.k2LatestCommentsBlock ul li span.lcItemCategory {}

/* Top Commenters */
div.k2TopCommentersBlock {}
div.k2TopCommentersBlock ul {}
div.k2TopCommentersBlock ul li {}
div.k2TopCommentersBlock ul li.lastItem {}
div.k2TopCommentersBlock ul li a.tcAvatar img {}
div.k2TopCommentersBlock ul li a.tcLink {}
div.k2TopCommentersBlock ul li a.tcLink:hover {}
div.k2TopCommentersBlock ul li span.tcUsername {}
div.k2TopCommentersBlock ul li span.tcCommentsCounter {}
div.k2TopCommentersBlock ul li a.tcLatestComment {}
div.k2TopCommentersBlock ul li a.tcLatestComment:hover {}
div.k2TopCommentersBlock ul li span.tcLatestCommentDate {color:#999;}



/*----------------------------------------------------------------------
	Modules: mod_k2_content
----------------------------------------------------------------------*/

div.k2ItemsBlock {}

div.k2ItemsBlock p.modulePretext {}

div.k2ItemsBlock ul {}
div.k2ItemsBlock ul li {}
div.k2ItemsBlock ul li a {}
div.k2ItemsBlock ul li a:hover {}
div.k2ItemsBlock ul li.lastItem {}

div.k2ItemsBlock ul li a.moduleItemTitle {}
div.k2ItemsBlock ul li a.moduleItemTitle:hover {}

div.k2ItemsBlock ul li div.moduleItemAuthor {}
div.k2ItemsBlock ul li div.moduleItemAuthor a {}
div.k2ItemsBlock ul li div.moduleItemAuthor a:hover {}

div.k2ItemsBlock ul li a.moduleItemAuthorAvatar img {}

div.k2ItemsBlock ul li div.moduleItemIntrotext {display:block;padding:4px 0;line-height:120%;}
div.k2ItemsBlock ul li div.moduleItemIntrotext a.moduleItemImage img {float:right;margin:2px 0 4px 4px;padding:0;border:2px solid #ddd;}

div.k2ItemsBlock ul li div.moduleItemExtraFields {}
	div.moduleItemExtraFields ul {}
	div.moduleItemExtraFields ul li {}
	div.moduleItemExtraFields ul li span.moduleItemExtraFieldsLabel {display:block;float:left;font-weight:bold;margin:0 4px 0 0;width:30%;}
	div.moduleItemExtraFields ul li span.moduleItemExtraFieldsValue {}

div.k2ItemsBlock ul li div.moduleItemVideo {}
div.k2ItemsBlock ul li div.moduleItemVideo span.moduleItemVideoCaption {}
div.k2ItemsBlock ul li div.moduleItemVideo span.moduleItemVideoCredits {}

div.k2ItemsBlock ul li span.moduleItemDateCreated {}

div.k2ItemsBlock ul li a.moduleItemCategory {}

div.k2ItemsBlock ul li div.moduleItemTags {}
div.k2ItemsBlock ul li div.moduleItemTags b {}
div.k2ItemsBlock ul li div.moduleItemTags a {padding:0 2px;}
div.k2ItemsBlock ul li div.moduleItemTags a:hover {}

div.k2ItemsBlock ul li div.moduleAttachments {}

div.k2ItemsBlock ul li a.moduleItemComments {border-right:1px solid #ccc;padding:0 4px 0 0;margin:0 8px 0 0;}
div.k2ItemsBlock ul li a.moduleItemComments:hover {}
div.k2ItemsBlock ul li span.moduleItemHits {border-right:1px solid #ccc;padding:0 4px 0 0;margin:0 8px 0 0;}
div.k2ItemsBlock ul li a.moduleItemReadMore {}
div.k2ItemsBlock ul li a.moduleItemReadMore:hover {}

div.k2ItemsBlock a.moduleCustomLink {}
div.k2ItemsBlock a.moduleCustomLink:hover {}



/*----------------------------------------------------------------------
	Modules: mod_k2_user (mod_k2_login will be removed in v2.6)
----------------------------------------------------------------------*/

div.k2LoginBlock {}
	div.k2LoginBlock p.preText {}

	div.k2LoginBlock fieldset.input {margin:0;padding:0 0 8px 0;}
	div.k2LoginBlock fieldset.input p {margin:0;padding:0 0 4px 0;}
	div.k2LoginBlock fieldset.input p label {display:block;}
	div.k2LoginBlock fieldset.input p input {display:block;}
	div.k2LoginBlock fieldset.input p#form-login-remember label,
	div.k2LoginBlock fieldset.input p#form-login-remember input {display:inline;}
	div.k2LoginBlock fieldset.input input.button {}

	div.k2LoginBlock ul {}
	div.k2LoginBlock ul li {}

	div.k2LoginBlock p.postText {}

div.k2UserBlock {}
	div.k2UserBlock p.ubGreeting {border-bottom:1px dotted #ccc;}
	div.k2UserBlock div.k2UserBlockDetails a.ubAvatar img {}
	div.k2UserBlock div.k2UserBlockDetails span.ubName {display:block;font-weight:bold;font-size:14px;}
	div.k2UserBlock div.k2UserBlockDetails span.ubCommentsCount {}

	div.k2UserBlock ul.k2UserBlockActions {}
		div.k2UserBlock ul.k2UserBlockActions li {}
		div.k2UserBlock ul.k2UserBlockActions li a {}
		div.k2UserBlock ul.k2UserBlockActions li a:hover {}

	div.k2UserBlock ul.k2UserBlockRenderedMenu {}
		div.k2UserBlock ul.k2UserBlockRenderedMenu li {}
		div.k2UserBlock ul.k2UserBlockRenderedMenu li a {}
		div.k2UserBlock ul.k2UserBlockRenderedMenu li a:hover {}
		div.k2UserBlock ul.k2UserBlockRenderedMenu li ul {} /* 2nd level ul */
		div.k2UserBlock ul.k2UserBlockRenderedMenu li ul li {}
		div.k2UserBlock ul.k2UserBlockRenderedMenu li ul li a {}
		div.k2UserBlock ul.k2UserBlockRenderedMenu li ul ul {} /* 3rd level ul (and so on...) */
		div.k2UserBlock ul.k2UserBlockRenderedMenu li ul ul li {}
		div.k2UserBlock ul.k2UserBlockRenderedMenu li ul ul li a {}

	div.k2UserBlock form {}
	div.k2UserBlock form input.ubLogout {}



/*----------------------------------------------------------------------
	Modules: mod_k2_tools
----------------------------------------------------------------------*/

/* --- Archives --- */
div.k2ArchivesBlock {}
div.k2ArchivesBlock ul {}
div.k2ArchivesBlock ul li {}
div.k2ArchivesBlock ul li a {}
div.k2ArchivesBlock ul li a:hover {}

/* --- Authors --- */
div.k2AuthorsListBlock {}
div.k2AuthorsListBlock ul {}
div.k2AuthorsListBlock ul li {}
div.k2AuthorsListBlock ul li a.abAuthorAvatar img {}
div.k2AuthorsListBlock ul li a.abAuthorName {}
div.k2AuthorsListBlock ul li a.abAuthorName:hover {}
div.k2AuthorsListBlock ul li a.abAuthorLatestItem {display:block;clear:both;}
div.k2AuthorsListBlock ul li a.abAuthorLatestItem:hover {}
div.k2AuthorsListBlock ul li span.abAuthorCommentsCount {}

/* --- Breadcrumbs --- */
div.k2BreadcrumbsBlock {}
div.k2BreadcrumbsBlock span.bcTitle {padding:0 4px 0 0;color:#999;}
div.k2BreadcrumbsBlock a {}
div.k2BreadcrumbsBlock a:hover {}
div.k2BreadcrumbsBlock span.bcSeparator {padding:0 4px;font-size:14px;}

/* --- Calendar --- */
div.k2CalendarBlock {height:190px;margin-bottom:8px;} /* use this height value so that the calendar height won't change on Month change via ajax */
div.k2CalendarLoader {background:#fff url(/components/com_k2/images/system/k2CalendarLoader.gif) no-repeat 50% 50%;}
table.calendar {margin:0 auto;background:#fff;border-collapse:collapse;}
table.calendar tr td {text-align:center;vertical-align:middle;padding:2px;border:1px solid #f4f4f4;background:#fff;}
table.calendar tr td.calendarNavMonthPrev {background:#f3f3f3;text-align:left;}
table.calendar tr td.calendarNavMonthPrev a {font-size:20px;text-decoration:none;}
table.calendar tr td.calendarNavMonthPrev a:hover {font-size:20px;text-decoration:none;}
table.calendar tr td.calendarCurrentMonth {background:#f3f3f3;}
table.calendar tr td.calendarNavMonthNext {background:#f3f3f3;text-align:right;}
table.calendar tr td.calendarNavMonthNext a {font-size:20px;text-decoration:none;}
table.calendar tr td.calendarNavMonthNext a:hover {font-size:20px;text-decoration:none;}
table.calendar tr td.calendarDayName {background:#e9e9e9;font-size:11px;width:14.2%;}
table.calendar tr td.calendarDateEmpty {background:#fbfbfb;}
table.calendar tr td.calendarDate {}
table.calendar tr td.calendarDateLinked {padding:0;}
table.calendar tr td.calendarDateLinked a {display:block;padding:2px;text-decoration:none;background:#eee;}
table.calendar tr td.calendarDateLinked a:hover {display:block;background:#135cae;color:#fff;padding:2px;text-decoration:none;}
table.calendar tr td.calendarToday {background:#135cae;color:#fff;}
table.calendar tr td.calendarTodayLinked {background:#135cae;color:#fff;padding:0;}
table.calendar tr td.calendarTodayLinked a {display:block;padding:2px;color:#fff;text-decoration:none;}
table.calendar tr td.calendarTodayLinked a:hover {display:block;background:#BFD9FF;padding:2px;text-decoration:none;}

/* --- Category Tree Select Box --- */
div.k2CategorySelectBlock {}
div.k2CategorySelectBlock form select {width:auto;}
div.k2CategorySelectBlock form select option {}

/* --- Category List/Menu --- */
div.k2CategoriesListBlock {}
div.k2CategoriesListBlock ul {}
div.k2CategoriesListBlock ul li {}
div.k2CategoriesListBlock ul li a {}
div.k2CategoriesListBlock ul li a:hover {}
div.k2CategoriesListBlock ul li a span.catTitle {padding-right:4px;}
div.k2CategoriesListBlock ul li a span.catCounter {}
div.k2CategoriesListBlock ul li a:hover span.catTitle {}
div.k2CategoriesListBlock ul li a:hover span.catCounter {}
div.k2CategoriesListBlock ul li.activeCategory {}
div.k2CategoriesListBlock ul li.activeCategory a {font-weight:bold;}

	/* Root level (0) */
	ul.level0 {}
	ul.level0 li {}
	ul.level0 li a {}
	ul.level0 li a:hover {}
	ul.level0 li a span {}
	ul.level0 li a:hover span {}

		/* First level (1) */
		ul.level1 {}
		ul.level1 li {}
		ul.level1 li a {}
		ul.level1 li a:hover {}
		ul.level1 li a span {}
		ul.level1 li a:hover span {}

			/* n level (n) - like the above... */

/* --- Search Box --- */
div.k2SearchBlock {position:relative;}
div.k2SearchBlock form {}
div.k2SearchBlock form input.inputbox {}
div.k2SearchBlock form input.button {}
div.k2SearchBlock form input.k2SearchLoading {background:url(/components/com_k2/images/system/searchLoader.gif) no-repeat 100% 50%;}
div.k2SearchBlock div.k2LiveSearchResults {display:none;background:#fff;position:absolute;z-index:99;border:1px solid #ccc;margin-top:-1px;}
	/* Live search results (fetched via ajax) */
	div.k2SearchBlock div.k2LiveSearchResults ul.liveSearchResults {list-style:none;margin:0;padding:0;}
	div.k2SearchBlock div.k2LiveSearchResults ul.liveSearchResults li {border:none;margin:0;padding:0;}
	div.k2SearchBlock div.k2LiveSearchResults ul.liveSearchResults li a {display:block;padding:1px 2px;border-top:1px dotted #eee;}
	div.k2SearchBlock div.k2LiveSearchResults ul.liveSearchResults li a:hover {background:#fffff0;}
	
/* --- Tag Cloud --- */
div.k2TagCloudBlock {padding:8px 0;}
div.k2TagCloudBlock a {padding:4px;float:left;display:block;}
div.k2TagCloudBlock a:hover {padding:4px;float:left;display:block;background:#135cae;color:#fff;text-decoration:none;}

/* --- Custom Code --- */
div.k2CustomCodeBlock {}



/*----------------------------------------------------------------------
	Modules: mod_k2_users
----------------------------------------------------------------------*/

div.k2UsersBlock {}
div.k2UsersBlock ul {}
div.k2UsersBlock ul li {}
div.k2UsersBlock ul li.lastItem {}
div.k2UsersBlock ul li a.ubUserAvatar img {}
div.k2UsersBlock ul li a.ubUserName {}
div.k2UsersBlock ul li a.ubUserName:hover {}
div.k2UsersBlock ul li div.ubUserDescription {}
div.k2UsersBlock ul li div.ubUserAdditionalInfo {}
	a.ubUserFeedIcon,
	a.ubUserFeedIcon:hover {display:inline-block;margin:0 2px 0 0;padding:0;width:16px;height:16px;background:url(/components/com_k2/images/fugue/feed.png) no-repeat 50% 50%;}
	a.ubUserFeedIcon span,
	a.ubUserFeedIcon:hover span {display:none;}
	a.ubUserURL,
	a.ubUserURL:hover {display:inline-block;margin:0 2px 0 0;padding:0;width:16px;height:16px;background:url(/components/com_k2/images/fugue/globe.png) no-repeat 50% 50%;}
	a.ubUserURL span,
	a.ubUserURL:hover span {display:none;}
	span.ubUserEmail {display:inline-block;margin:0 2px 0 0;padding:0;width:16px;height:16px;background:url(/components/com_k2/images/fugue/mail.png) no-repeat 50% 50%;overflow:hidden;}
	span.ubUserEmail a {display:inline-block;margin:0;padding:0;width:16px;height:16px;text-indent:-9999px;}

div.k2UsersBlock ul li h3 {clear:both;margin:8px 0 0 0;padding:0;}
div.k2UsersBlock ul li ul.ubUserItems {}
div.k2UsersBlock ul li ul.ubUserItems li {}



/* --- END --- */

@CHARSET "UTF-8";
.itp-sharepoint{
    display:block !important;        
}

.itp-sharepoint-tw{
    float:left;
    margin:10px 5px 0;
}

.itp-sharepoint-tbr{
    float:left;
    margin:10px;
}

.itp-sharepoint-fbl{
    float:left;
    margin:10px;
}
.itp-sharepoint-digg{
    float:left;
    margin-right:15px;
    margin-top:10px;
}
.itp-sharepoint-su{
    float:left;
    margin-right:14px;
    margin-top:11px;
}
.itp-sharepoint-lin{
    float:left;
    margin-right:14px;
    margin-top:10px;
}

.itp-sharepoint-gone{
    float:left;
    margin-left:5px;
    margin-right:10px;
    margin-top:10px;
}

.itp-sharepoint-retweetme{
    float:left;
    margin-top:10px;
}

.itp-sharepoint-reddit{
    float:left;
    margin-top:10px;
}
.itp-sharepoint-reddit a:hover{
    text-decoration: none !important;
    background: none !important;
}

.itp-sharepoint-pinterest {
    float:left;
    margin-left:10px;
    margin-top:10px;
}

.itp-sharepoint-buffer {
    float:left;
    margin-top:10px;
}
/**
 * @version   3.2.12 October 30, 2011
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

/* Reset */
html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, font, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, caption {margin: 0;padding: 0;border: 0;outline: 0;font-size: 100%;background: transparent;}

/* Grid Container */
.rt-container {margin: 0 auto;width: 960px;}
body {min-width: 960px;}

/* Grid Block */
.rt-block {padding: 15px;margin-bottom: 10px;position: relative;}
#rt-content-top .rt-alpha, #rt-content-bottom .rt-alpha {margin-left: 0;}
#rt-content-top .rt-omega, #rt-content-bottom .rt-omega {margin-right: 0;}

/* Layout */
#rt-logo {width: 185px;height: 115px;display: block;}
#rt-content-top, #rt-content-bottom {overflow: hidden;}
#rocket {display: block;width: 92px;height: 16px;margin: 0 auto;}
#rt-copyright {text-align: center;}

/* Menu */
#rt-menu .rt-container {height: 3em;}
#rt-menu ul.menu {list-style: none;margin: 0 10px;padding: 0;}
#rt-menu ul.menu li {float: left;padding: 0;background: none;}
#rt-menu ul.menu li a {font-weight: bold;line-height: 3em;display: block;padding: 0 15px;font-size: 1em;}
#rt-menu ul.menu ul {display: none;}

/* Font Stacks */
.font-family-optima {font-family: Optima, Lucida, 'MgOpen Cosmetica', 'Lucida Sans Unicode', sans-serif;}
.font-family-geneva {font-family: Geneva, Tahoma, "Nimbus Sans L", sans-serif;}
.font-family-helvetica {font-family: Helvetica, Arial, FreeSans, sans-serif;}
.font-family-lucida {font-family: "Lucida Grande",Helvetica,Verdana,sans-serif;}
.font-family-georgia {font-family: Georgia, sans-serif;}
.font-family-trebuchet {font-family: "Trebuchet MS", sans-serif;}
.font-family-palatino {font-family: "Palatino Linotype", "Book Antiqua", Palatino, "Times New Roman", Times, serif;}

/* Typography */
body {font-size: 12px;line-height: 1.7em;font-family: Helvetica,Arial, Sans-Serif;}
body.font-size-is-xlarge {font-size: 15px;line-height: 1.7em;}
body.font-size-is-large {font-size: 14px;line-height: 1.7em;}
body.font-size-is-default {font-size: 12px;line-height: 1.7em;}
body.font-size-is-small {font-size: 11px;line-height: 1.7em;}
body.font-size-is-xsmall {font-size: 10px;line-height: 1.7em;}
form {margin: 0;padding: 0;}
p {margin: 0 0 15px 0;}
h1, h2, h3, h4, h5 {margin: 15px 0;line-height: 1.1em;}
h1 {font-size: 260%;}
h2 {font-size: 200%;}
h2.title {font-size: 170%;}
h3 {font-size: 175%;}
h4 {font-size: 120%;}
h5 {font-size: 120%;}
a {text-decoration: none;outline: none;}
code {color: #000;margin: -1px 0 0 0;font: 10px Courier;}
blockquote {font: italic 16px/22px Georgia, Serif;}
.left-1, .left-2, .left-3, .left-4, .left-5, .left-6, .left-7, .left-8, .left-9, .left-10, .right-11 {float: left;margin-right: 50px;margin-bottom: 15px;display: inline;position: relative;}
.right-1, .right-2, .right-3, .right-4, .right-5, .right-6, .right-7, .right-8, .right-9, .right-10, .right-11 {float: right;margin-left: 50px;margin-bottom: 15px;display: inline;position: relative;}
.date-block {padding: 15px;}

/* Lists */
ul, ol {padding-left: 15px;}
ul li {padding: 0;margin: 0;}
ul li a {font-size: 1.2em;line-height: 1.8em;}
ul ul {margin-left: 25px;padding: 5px 0;}
ul li.author {margin: 0;letter-spacing: 1px;list-style: none;font-weight: bold;text-align: right;}
ul li.date {margin: 0;letter-spacing: 1px;list-style: none;text-align: right;font-weight: bold;}
ul li.comments {list-style: none;text-align: right;font-weight: bold;}
ul li.author span, ul li.date span, ul li.comments span {display: block;font-weight: normal;margin-bottom: 10px;line-height: 1em;}

/* RTL */
body.rtl {direction: rtl;}
body.rtl #rt-menu ul.menu {float: right;}
body.rtl #rt-menu ul.menu li {float: right;}
body.rtl #rt-content-top .rt-alpha, body.rtl #rt-content-bottom .rt-alpha {margin-right: 0;margin-left: 10px;}
body.rtl #rt-content-top .rt-omega, body.rtl #rt-content-bottom .rt-omega {margin-left: 0;margin-right: 10px;}
body.rtl {min-width: inherit;}

/* Style */
html,body {margin-bottom: 1px;}
body {color: #333;}
#rt-header, #rt-bottom {color: #aaa;}
.rt-container {background: #fff;} 
a:hover {color: #000;}
#rt-logo {background: url(/libraries/gantry/images/header-assets.png) 0 0 no-repeat;}
#rt-menu ul.menu li a {color: #fff;}
#rt-menu ul.menu li a:hover {background: #444;color: #fff;}
#rt-menu ul.menu li.active a, #rt-menu ul.menu li.active a:hover {background: #fff;color: #000;}
#rt-footer, #rt-copyright {color: #ddd;}
#rt-sidebar-a {background-color:#e0e0e0;}
#rt-sidebar-b {background-color:#e9e9e9;}
#rt-sidebar-c {background-color:#f0f0f0;}
#rocket {background: url(/libraries/gantry/images/rocket.png) 0 0 no-repeat;}

#gantry-viewswitcher {background-image: url(/libraries/gantry/images/iphone/switcher.png); background-repeat: no-repeat; background-position: top left; width: 60px; height: 20px;display:block;position:absolute;right: 10px;top:40%;}
#gantry-viewswitcher.off {background-position: bottom left;}
#gantry-viewswitcher span {display: none;}

/* Clear Set */
html body * span.clear, html body * div.clear, html body * li.clear, html body * dd.clear {background: none;border: 0;clear: both;display: block;float: none;font-size: 0;list-style: none;margin: 0;padding: 0;overflow: hidden;visibility: hidden;width: 0;height: 0;}
.clearfix:after {clear: both;content: '.';display: block;visibility: hidden;height: 0;}
.clearfix {display: inline-block;}
* html .clearfix {height: 1%;}
.clearfix {display: block;}

/* Debug only */
#debug #rt-main {overflow:hidden;border-bottom: 4px solid #666; margin-top:15px;position:relative}
#debug .status {position: absolute;background:#333;opacity:.3;padding:0px 15px;z-index:10000;color:#fff;font-weight:bold;font-size:150%}
/**
 * @version   3.2.12 October 30, 2011
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

/* 12 Grid */
.rt-grid-1, .rt-grid-2, .rt-grid-3, .rt-grid-4, .rt-grid-5, .rt-grid-6, .rt-grid-7, .rt-grid-8, .rt-grid-9, .rt-grid-10, .rt-grid-11, .rt-grid-12 {display: inline;float: left;position: relative;margin-left: 10px;margin-right: 10px;}
.rt-container .rt-grid-1 {width: 60px;}
.rt-container .rt-grid-2 {width: 140px;}
.rt-container .rt-grid-3 {width: 220px;}
.rt-container .rt-grid-4 {width: 300px;}
.rt-container .rt-grid-5 {width: 380px;}
.rt-container .rt-grid-6 {width: 460px;}
.rt-container .rt-grid-7 {width: 540px;}
.rt-container .rt-grid-8 {width: 620px;}
.rt-container .rt-grid-9 {width: 700px;}
.rt-container .rt-grid-10 {width: 780px;}
.rt-container .rt-grid-11 {width: 860px;}
.rt-container .rt-grid-12 {width: 940px;}

/* Grid Push */
.rt-container .rt-push-1 {left: 80px;}
.rt-container .rt-push-2 {left: 160px;}
.rt-container .rt-push-3 {left: 240px;}
.rt-container .rt-push-4 {left: 320px;}
.rt-container .rt-push-5 {left: 400px;}
.rt-container .rt-push-6 {left: 480px;}
.rt-container .rt-push-7 {left: 560px;}
.rt-container .rt-push-8 {left: 640px;}
.rt-container .rt-push-9 {left: 720px;}
.rt-container .rt-push-10 {left: 800px;}
.rt-container .rt-push-11 {left: 880px;}

/* Grid Pull */
.rt-container .rt-pull-1 {left: -80px;}
.rt-container .rt-pull-2 {left: -160px;}
.rt-container .rt-pull-3 {left: -240px;}
.rt-container .rt-pull-4 {left: -320px;}
.rt-container .rt-pull-5 {left: -400px;}
.rt-container .rt-pull-6 {left: -480px;}
.rt-container .rt-pull-7 {left: -560px;}
.rt-container .rt-pull-8 {left: -640px;}
.rt-container .rt-pull-9 {left: -720px;}
.rt-container .rt-pull-10 {left: -800px;}
.rt-container .rt-pull-11 {left: -880px;}

/* Prefix for left nudging */
.rt-container .rt-prefix-1 {padding-left:80px;}
.rt-container .rt-prefix-2 {padding-left:160px;}
.rt-container .rt-prefix-3 {padding-left:240px;}
.rt-container .rt-prefix-4 {padding-left:320px;}
.rt-container .rt-prefix-5 {padding-left:400px;}
.rt-container .rt-prefix-6 {padding-left:480px;}
.rt-container .rt-prefix-7 {padding-left:560px;}
.rt-container .rt-prefix-8 {padding-left:640px;}
.rt-container .rt-prefix-9 {padding-left:720px;}
.rt-container .rt-prefix-10 {padding-left:800px;}
.rt-container .rt-prefix-11 {padding-left:880px;}

/* Extras */
.left-1, .right-1 {width: 30px;}
.left-2, .right-2 {width: 110px;}
.left-3, .right-3 {width: 190px;}
.left-4, .right-4 {width: 270px;}
.left-5, .right-5 {width: 350px;}
.left-6, .right-6 {width: 430px;}
.left-7, .right-7 {width: 510px;}
.left-8, .right-8 {width: 590px;}
.left-9, .right-9 {width: 670px;}
.left-10, .right-10 {width: 750px;}
.left-11, .right-11 {width: 830px;}
/**
 * @version   3.2.12 October 30, 2011
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

/* Headings */
h1, h2 {letter-spacing: -2px;line-height: 1em;}
.module-title h2, h3, h4, h5 {letter-spacing: -1px;line-height: 1em;}
.componentheading {font-weight: bold;display: block;margin-bottom: 15px;}
.contentheading {font-size: 140%;font-weight: bold;margin-bottom: 15px;display: block;}

/* Section Tables */
.sectiontableheader {font-weight: bold;padding: 3px;line-height: 25px;text-align: left;}
.sectiontablefooter {padding-bottom: 8px;}
tr.sectiontableentry0 td, tr.sectiontableentry1 td, tr.sectiontableentry2 td, td.sectiontableentry0, td.sectiontableentry1, td.sectiontableentry2 {text-align: left;padding: 6px 5px;vertical-align: middle;}
tr.sectiontableentry0 td, td.sectiontableentry0, tr.sectiontableentry1 td, td.sectiontableentry1, tr.sectiontableentry2 td, td.sectiontableentry2 {height: 30px;}
.contentpane, .contentpaneopen {width: 100%;}

/* Column Layout */
.component-content .cols-1 {display: block;float: none !important;margin: 0 !important;} 
.component-content .cols-2 .column-1, .component-content .cols-2 .column-2 {width: 50%;float: left;}
.component-content .cols-3 .column-1, .component-content .cols-3 .column-2, .component-content .cols-3 .column-3 {float: left;width: 33.3%;padding: 0;margin: 0;}
.component-content .items-row {overflow: hidden;margin-bottom: 10px !important;}
.component-content .cols-4 .column-1, .component-content .cols-4 .column-2, .component-content .cols-4 .column-13, .component-content .cols-4 .column-4 {width: 25%;padding: 0;margin: 0;float: left;}
.component-content .cols-2 .rt-article, .component-content .cols-3 .rt-article, .component-content .cols-4 .rt-article {margin: 0 10px 20px 10px;}
.component-content .cols-2 .column-1 .rt-article, .component-content .cols-3 .column-1 .rt-article, .component-content .cols-4 .column-1 .rt-article {margin-left: 0;}
.component-content .cols-2 .column-2 .rt-article, .component-content .cols-3 .column-3 .rt-article, .component-content .cols-4 .column-4 .rt-article {margin-right: 0;}

/* Category Layout */
.component-content .rt-blog .rt-description {margin: 10px 0 15px 0;padding-bottom: 20px;border-bottom: 1px #c8c8c8 dotted;}
.component-content .rt-article-bg {padding-bottom: 5px;border-bottom: 1px #c8c8c8 dotted;}
.component-content .rt-article-links {margin: 10px 0px 10px 0px;}
.component-content .category-desc {padding: 0 5px;margin: 10px 0 25px;}
.component-content .cat-children .category-desc {margin: 10px 0 25px;}
.component-content ul.subcategories {margin: 20px 20px 20px 10px;}
.component-content .subcategories-link {font-weight: bold}
.component-content ul {list-style-position: outside;list-style-type: square;padding: 0 0 0 15px;margin: 10px 0;}
.component-content ol {padding: 0 0 0 20px;margin: 10px 0;list-style-position: outside;}
.component-content ul li, .component-content ol li {padding: 0;line-height: 1.7em;margin: 0;}
.component-content .category-list {padding: 0 5px;display: block;}
.component-content .categories-list {padding: 0 5px;}
.component-content .categories-list ul {margin: 0 0 0 20px;padding: 0;list-style: none;}
.component-content .categories-list ul li {padding: 5px;}
.component-content .categories-list ul ul {margin-left: 15px;}
.component-content .category-desc {line-height: 1.7em;margin: 10px 0;padding-left: 0;}
.component-content .small {font-size: 0.85em;margin: 0 0 20px;}
.component-content .image-left {float: left;margin: 0 15px 5px 0;}
.component-content .image-right {float: right;margin: 0 0 5px 15px;}
.component-content .archive {padding: 0 5px;}
.component-content .archive form {padding: 0 5px;}
.component-content ul#archive-items {margin: 20px 0;list-style-type: none;padding: 0;}
.component-content ul#archive-items li.row0, .component-content ul#archive-items li.row1 {padding: 10px 0;margin: 10px 0;}

/* Article Layout */
.component-content .title {width: auto;font-size: 260%;line-height: 1.1em;}
.component-content span.edit {margin-left: 10px;float: left;}
.component-content .rt-articleinfo {margin-bottom: 15px;}
.component-content .rt-category, .component-content .rt-date-modified, .component-content .rt-date-published, .component-content .rt-author, .component-content .rt-date-posted, .component-content .rt-hits {display: block;font-size: 95%;}
.component-content .rt-author {font-style: italic;}
.component-content .rt-date-created, .component-content .rt-date-published {font-weight: bold;}
.component-content .rt-description {margin: 10px 0px 10px 0px;overflow: hidden;}
.component-content .rt-description img.left {margin-right: 15px;float: left;}
.component-content .rt-description img.right {margin-left: 15px;float: right;}
.component-content .filter {margin: 10px 0;}
.component-content span.number {color: #969696;font-style: italic;}
.component-content .rt-article-icons {width: 65px;overflow: hidden;float: right;}
.component-content .rt-article-icons ul {margin: 0;padding: 0;list-style: none;}
.component-content .rt-article-icons ul li {margin: 0;padding: 0}
.component-content .rt-article-icons ul li a {display: block;width: 16px;height: 16px;float: right;margin-left: 3px;background-image: url(/libraries/gantry/images/typography.png);background-repeat: no-repeat;}
.component-content .rt-article-icons ul li img {display: none;}
.component-content .print-icon a {background-position: 0 0;}
.component-content .email-icon a {background-position: -16px 0;}
.component-content .edit-icon a {background-position: -48px 0;margin-left: 0 !important;margin-right: 5px;}
.component-content .rt-article-cat {font-weight: bold;margin-top: 15px;margin-bottom: 0;}

/* Editing */
.component-content .edit #editor-xtd-buttons a:link, .component-content .edit #editor-xtd-buttons a:visited, .component-content .edit #editor-xtd-buttons a:hover {color: #323232;}
.component-content .edit .inputbox, .component-content .edit textarea {border: 1px solid #ddd;}
.component-content .edit legend {font-size: 150%;}
.component-content .edit form#adminForm fieldset {padding: 20px 15px;margin: 10px 0 15px 0;}
.component-content .formelm {margin: 5px 0;}
.component-content .formelm label {width: 9em;display: inline-block;vertical-align: top;}
.component-content form#adminForm .formelm-area {padding: 5px 0;}
.component-content form#adminForm .formelm-area label {vertical-align: top;display: inline-block;width: 7em}
.component-content .formelm-buttons {text-align: right;margin-bottom: 10px}
.component-content .button2-left {float: left;margin-right: 5px;margin-top: 10px;}
.component-content .button2-left a {background: #eee;padding: 4px;margin: 0;line-height: 1.2em;border: solid 1px #ddd;font-weight: bold;text-decoration: none;}

/* User */
#form-login .inputbox, #com-form-login .inputbox, #josForm .inputbox {border: 1px solid #ddd;font-size: 1.2em;padding: 2px;margin: 0;}
#com-form-login fieldset div, #josForm fieldset div {margin-bottom: 10px;}
#form-login ul {margin-top: 10px;}
#form-login p {margin-bottom: 10px;}
#form-login .user-greeting {font-weight: bold;font-size: 120%;margin-bottom: 15px;}
.component-content .user label.label-left, .component-content .user span.label-left {display: block;width: 130px;float: left;font-weight: bold;font-size: 120%;}
.col12 .rt-grid-2 #form-login .inputbox {width: 104px;}
.col12 .rt-grid-3 #form-login .inputbox {width: 184px;}
.col12 .rt-grid-4 #form-login .inputbox {width: 264px;}
.col12 .rt-grid-5 #form-login .inputbox {width: 344px;}
.col12 .rt-grid-6 #form-login .inputbox {width: 424px;}
.col12 .rt-grid-7 #form-login .inputbox {width: 504px;}
.col12 .rt-grid-8 #form-login .inputbox {width: 584px;}
.col12 .rt-grid-9 #form-login .inputbox {width: 664px;}
.col12 .rt-grid-10 #form-login .inputbox {width: 744px;}
.col12 .rt-grid-12 #form-login .inputbox {width: 904px;}
.col16 .rt-grid-2 #form-login .inputbox {width: 64px;}
.col16 .rt-grid-3 #form-login .inputbox {width: 124px;}
.col16 .rt-grid-4 #form-login .inputbox {width: 184px;}
.col16 .rt-grid-5 #form-login .inputbox {width: 244px;}
.col16 .rt-grid-6 #form-login .inputbox {width: 304px;}
.col16 .rt-grid-7 #form-login .inputbox {width: 364px;}
.col16 .rt-grid-8 #form-login .inputbox {width: 424px;}
.col16 .rt-grid-9 #form-login .inputbox {width: 484px;}
.col16 .rt-grid-10 #form-login .inputbox {width: 544px;}
.col16 .rt-grid-11 #form-login .inputbox {width: 604px;}
.col16 .rt-grid-12 #form-login .inputbox {width: 664px;}
.col16 .rt-grid-13 #form-login .inputbox {width: 724px;}
.col16 .rt-grid-14 #form-login .inputbox {width: 784px;}
.col16 .rt-grid-15 #form-login .inputbox {width: 844px;}
#users-profile-core, #users-profile-custom {margin: 10px 0 15px 0;padding: 15px;}
#users-profile-core dt, #users-profile-custom dt {float: left;width: 10em;padding: 3px 0;}
#users-profile-core dd, #users-profile-custom dd {padding: 3px 0;}
#member-profile fieldset, .registration fieldset {margin: 10px 0 15px 0;padding: 15px;}
#users-profile-core legend, .profile-edit legend, .registration legend {font-weight: bold;}
.component-content #member-registration {padding: 0 5px;}
.component-content #member-registration fieldset {border: solid 1px #ddd;}
.component-content form fieldset dt {clear: left;float: left;width: 12em;padding: 3px 0;}
.component-content form fieldset dd {float: left;padding: 3px 0;}

/* Tables */
.component-content table {border-collapse: collapse;}
.component-content table.weblinks, .component-content table.category {font-size: 1em;margin: 10px 10px 20px 0px;width: 99%;}
.component-content table.weblinks td {border-collapse: collapse;}
.component-content table.weblinks td, .component-content table.category td {padding: 7px;}
.component-content table.weblinks th, .component-content table.category th {padding: 7px;text-align: left;}
.component-content td.num {vertical-align: top;text-align: left;}
.component-content td.hits {vertical-align: top;text-align: center;}
.component-content td p {margin: 0;line-height: 1.3em;}
.component-content .filter {margin: 10px 0;}
.component-content .display-limit, .component-content .filter {text-align: right;margin-right: 7px;}
.component-content table.category th a img {padding: 2px 10px;}
.component-content .filter-search {float: left;}
.component-content .filter-search .inputbox {width: 6em;}
.component-content legend.element-invisible {position: absolute;margin-left: -3000px;margin-top: -3000px;height: 0;}
.component-content .cat-items {margin-top: 20px;}

/* Contact */
.component-content .contact {padding: 0 5px;}
.component-content .contact-category {padding: 0 10px;}
.component-content .contact-category  .component-content #adminForm fieldset.filters {border: 0;padding: 0;}
.component-content .contact-image {margin: 10px 0;overflow: hidden;}
.component-content address {font-style: normal;margin: 10px 0;}
.component-content address span {display: block;}
.component-content .contact-address {margin: 20px 0 10px 0;}
.component-content .contact-email div {padding: 2px 0;margin: 0 0 10px 0;}
.component-content .contact-email label {width: 17em;float: left;}
.component-content span.contact-image {margin-bottom: 10px;overflow: hidden;display: block;}
.component-content #contact-textmsg {padding: 2px 0 10px 0;}
.component-content #contact-email-copy {float: left;margin-right: 10px;}
.component-content .contact .button {float: none !important;clear: left;display: block;margin: 20px 0 0 0;}
.component-content dl.tabs {float: left;margin: 50px 0 0 0;z-index: 50;clear: both;}
.component-content dl.tabs dt {float: left;padding: 4px 10px;border-left: 1px solid #ccc;border-right: 1px solid #ccc;border-top: 1px solid #ccc;margin-right: 3px;background: #f0f0f0;color: #666;}
.component-content dl.tabs dt.open {background: #F9F9F9;border-bottom: 1px solid #F9F9F9;z-index: 100;color: #000;}
.component-content div.current {clear: both;border: 1px solid #ccc;padding: 10px 10px;max-width: 500px;}
.component-content div.current dd {padding: 0;margin: 0;}
.component-content dl#content-pane.tabs {margin: 1px 0 0 0;}

/* Weblinks */
.component-content .weblinks fieldset div {overflow: hidden;}
.component-content .weblinks label.label-left {display: block;width: 150px;float: left;}

/* Search */
.component-content .searchintro {font-weight: normal;margin: 20px 0 20px;}
.component-content #searchForm {padding: 0;}
.component-content .form-limit {margin: 20px 0 0;text-align: right;padding: 0 10px 0 20px;}
.component-content .highlight {font-weight: bold;}
.component-content .ordering-box {float: none;}
.component-content .phrases-box {float: none;margin-bottom: 10px;}
.component-content .ordering-box .inputbox {width: auto !important}
.component-content .only, .component-content .phrases {margin: 10px 0 0 0px;padding: 15px 0;line-height: 1.3em;}
.component-content label.ordering {display: block;margin: 10px 0 10px 0;}
.component-content .word {padding: 0;}
.component-content .word input {font-weight: bold;}
.component-content .word label {font-weight: bold;}
.component-content fieldset.only label, .component-content fieldset.phrases label {margin: 0 10px 0 0;}
.component-content .ordering-box label.ordering {margin: 0 10px 5px 0;float: left;}
.component-content form .search label {display: none;}
.component-content dl.search-results dt.result-title {padding: 15px 15px 0px 5px;font-weight: bold;}
.component-content dl.search-results dd {padding: 2px 15px 2px 5px;}
.component-content dl.search-results dd.result-text {padding: 10px 15px 10px 5px;line-height: 1.7em;}
.component-content dl.search-results dd.result-created {padding: 2px 15px 15px 5px;}
.component-content dl.search-results dd.result-category {padding: 10px 15px 5px 5px;}

/* Accessibility */
#rt-accessibility .rt-desc {float: left;padding-right: 5px;}
#rt-accessibility #rt-buttons {float: left;}
#rt-accessibility .button {display: block;width:14px;height:14px;float: left;background: url(/libraries/gantry/images/typography.png);margin: 2px;}
#rt-accessibility .large .button {background-position: 0 -17px;}
#rt-accessibility .small .button {background-position: -15px -17px;}

/* Other */
.component-content .categorylist input {border: 1px solid #ddd;font-size: 1.2em;padding: 2px;margin: 0;}
.component-content th {padding: 5px;background: #ebebeb;border-bottom: 2px solid #ddd;font-weight : bold;}
.component-content tr.even td {padding: 5px;background: #f0f0f0;border-bottom: 1px solid #ddd;}
.component-content tr.odd td {padding: 5px;background: #fafafa;border-bottom: 1px solid #ddd;}
.rt-breadcrumb-surround {margin: 0;display: block;position:relative;overflow: hidden;height: 20px;padding: 15px 25px;}
#breadcrumbs-gantry {width: 11px;height: 11px;display: block;float: left;margin-top: 4px;margin-right: 8px;background: url(/libraries/gantry/images/home.png) 0 0 no-repeat;}
.component-content p.error {padding: 10px;}
.component-content .contentpaneopen_edit {float: left;}
.component-content table.contenttoc {padding: 10px;margin: 10px;float: right;}
.component-content table.contenttoc tr td {padding: 1px 0;}
.component-content .pagenavcounter {font-weight: bold;}
ul.latestnews, ul.mostread {padding-left: 10px;margin: 0;}
ul.latestnews li a, ul.mostread li a {padding: 2px 2px 2px 15px;display: block;text-decoration: none;}
#article-index {width: 25%;float: right;padding: 10px;margin: 10px 0px 20px 30px;}
#article-index h3 {margin: 0;font-size: 1em;}
#article-index ul {list-style-type: disc;}
.mod-languages ul li {display: inline;}
.stats-module dl {margin: 10px 0 10px 0;}
.stats-module dt {float: left;margin: 0 10px 0 0;font-weight: bold;}
.stats-module dt, .stats-module dd {padding: 2px 0 2px 0;}
.banneritem {margin: 10px 0;padding: 0;}
.banneritem a {font-weight: bold;}
p.syndicate {float: left;display: block;text-align: left;}
.phrases .inputbox {width: 10em;}
.phrases .inputbox option {padding: 2px;}

/* Newsflash */
.newsflash {margin: 0;}
.newsflash-horiz {overflow: hidden;list-style-type: none;margin: 0 5px;padding: 20px 10px;}
.newsflash-horiz li {float: left;width: 30%;margin: 0 1%;padding: 10px 5px;}
.newsflash-horiz li h4 {font-size: 1.4em;}
.newsflash-horiz li img {display: block;margin-bottom: 10px;}
.newsflash-vert {padding: 0;}

/* Newsfeeds */
.component-content .newsfeed {padding: 0 5px;}
.component-content ul.newsfeed {padding: 0;}
.component-content .newsfeed-item {padding: 5px 0 0 0;margin: 0;}
.component-content .newsfeed-item h5 a {font-size: 1.1em;font-weight: bold;}
.component-content .feed-item-description img {margin: 5px 10px 10px 0;}
.component-content dl.newsfeed-count dt, .component-content dl.newsfeed-count dd {display: inline;}
.component-content dl.weblink-count dt, .component-content dl.weblink-count dd {display: inline;}

/* Pagination */
.component-content .rt-pagination {margin: 10px 0;padding: 10px 0 10px 0px;}
.component-content .rt-pagination ul {list-style-type: none;margin: 0;padding: 0;text-align: left;}
.component-content .rt-pagination li {display: inline;padding: 2px 5px;text-align: left;border: solid 1px #eee;margin: 0 2px;}
.component-content .rt-pagination li.pagination-start, .component-content .rt-pagination li.pagination-next, .component-content .rt-pagination li.pagination-end, .component-content .rt-pagination li.pagination-prev {border: 0;}
.component-content .rt-pagination li.pagination-start, .component-content .rt-pagination li.pagination-start span {padding: 0;}
.component-content p.counter {font-weight: bold;}

/* Pagenav */
.component-content .pagenav {list-style-type: none;padding: 0;overflow: hidden;}
.component-content .pagenav li {display: inline-block;padding: 0px;margin: 0;}
.component-content .pagenav li {line-height: 2em;}
.component-content .pagenav li a {display: inline;padding: 2px;text-decoration: none;}
.component-content .pagenav li.pagenav-prev {float: left;}
.component-content .pagenav li.pagenav-next {float: right;}

/* Tooltips */
.tool-tip {float: left;background: #ffc;border: 1px solid #D4D5AA;padding: 5px;max-width: 200px;color: #323232;}
.tool-title {padding: 0;margin: 0;font-size: 100%;font-weight: bold;margin-top: -15px;padding-top: 15px;padding-bottom: 5px;background: url(/libraries/system/images/selector-arrow.png) no-repeat;}
.tool-text {font-size: 100%;margin: 0;}

/* Mailto */
#mailto-window {background: #f5f5f5;padding: 15px;border: solid 1px #ddd;position: relative;}
#mailto-window label {width: 10em;}
.mailto-close {position: absolute;right: 0;top: 5px;background: none;}
.mailto-close a {min-width: 25px;display: block;min-height: 25px;overflow: visible;}
.mailto-close a span {position: absolute;left: -3000px;top: -3000px;display: inline;}
#mailto-window .inputbox {padding: 3px;}
#mailto-window p {margin-top: 20px;}
#mailto-window button {margin: 0 5px 0 0;}

/* System Messages */
/* OpenID icon style */
input.system-openid, input.com-system-openid { background: url(http://openid.net/images/login-bg.gif) no-repeat;background-color: #fff;background-position: 0 50%;color: #000;padding-left: 18px;}

/* Unpublished */
.system-unpublished {background: #e8edf1;border-top: 4px solid #c4d3df;border-bottom: 4px solid #c4d3df;}

/* System Messages */
#system-message { margin-bottom: 10px; padding: 0;}
#system-message dt { font-weight: bold; }
#system-message dd { margin: 0; font-weight: bold; }
#system-message dd ul { background: #BFD3E6; color: #0055BB; margin: 10px 0 10px 0; list-style: none; padding: 10px; border: 2px solid #84A7DB;}

/* System Standard Messages */
#system-message dt.message {display: none;}

/* System Error Messages */
#system-message dt.error {display: none;}
#system-message dd.error ul {color: #c00;background-color: #EBC8C9;border: 2px solid #DE7A7B;}

/* System Notice Messages */
#system-message dt.notice {display: none;}
#system-message dd.notice ul {color: #A08B4C;background: #F5ECC1;border: 2px solid #F0DC7E;}

/* Debug */
#system-debug {color: #ccc;background-color: #fff;padding: 10px;margin: 10px;}
#system-debug div {font-size: 11px;}
/**
 *  * @version   3.2.12 October 30, 2011
 *   * @author    RocketTheme http://www.rockettheme.com
 *    * @copyright Copyright (C) 2007 - 2011 RocketTheme, LLC
 *     * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *      */

/* Core */
body {
	color: #333;
	background: url(/templates/rt_gantry/images/body/main_bg.gif) center repeat fixed !important;}
#rt-header, #rt-bottom {color: #aaa;}
.rt-container {background: transparent;}
a:hover {color: #000;}
#rt-footer, #rt-copyright {color: #000;}

#rt-footer .rt-container {
	background:url('/templates/rt_gantry/images/header/header_bg.gif') !important;
}
#rt-sidebar-a {background-color: transparent;
margin-top: 16px;
}
#rt-sidebar-b {background-color: #e9e9e9;}
#rt-sidebar-c {background-color: #f0f0f0;}
#gantry-viewswitcher {margin: 5px auto;position: relative;top:auto;right:0;}

/* Navigation - Core */
#rt-menu ul.menu li a, .menutop li.root > .item, .menu-type-splitmenu .menutop li .item {color: #fff;}
.menutop li.parent.root > .item span, .menu-type-splitmenu .menutop li.parent .item span {background: url(/templates/rt_gantry/images/menus/menutop-daddy.png) 100% 50% no-repeat;}

/* Navigation - Hovers */
#rt-menu ul.menu li a:hover, .menutop li.root > .item:hover, .menutop li.active.root.f-mainparent-itemfocus > .item, .menutop li.root.f-mainparent-itemfocus > .item, .menu-type-splitmenu .menutop li:hover > .item {background: #444;color: #fff;}

/* Navigation - Active */
#rt-menu ul.menu li.active a, #rt-menu ul.menu li.active a:hover, .menutop li.root.active > .item, .menutop li.root.active > .item:hover, .menu-type-splitmenu .menutop li.active .item {background: #fff;color: #000;}

/* Navigation - Dropdowns */
.menutop .fusion-submenu-wrapper {background: #444;}
.menutop .fusion-submenu-wrapper.level3, .menutop .fusion-submenu-wrapper.level4, .menutop .fusion-submenu-wrapper.level5, .menutop .fusion-submenu-wrapper.level6 {background: #515151;}
.menutop ul li > .item {color: #fff;}
.menutop ul li > .item:hover, .menutop ul li.f-menuparent-itemfocus > .item {background: #333;color: #ccc;}
.menutop ul li > .daddy span {background: url(/templates/rt_gantry/images/menus/dropdown-daddy.png) 100% 50% no-repeat;}
.menutop .fusion-grouped ol li > .item {background: url(/templates/rt_gantry/images/menus/menu-arrow.png) 5px 50% no-repeat;}
.menutop li {
	font-family: Verdana, Verdana, Geneva, sans-serif !important;
	font-weight: bolder;
	font-size: 13px;
}
/* Header - General */
#rt-header {
	background:url('/templates/rt_gantry/images/header/header_bg.gif');
	border-bottom: 3px solid white;}
#rt-header .rt-container {
	background:url('/templates/rt_gantry/images/header/header_globe_big.gif') no-repeat 527px bottom !important;
	height:105px;}
#rt-header .rt-container #rt-logo {margin:6px 0px 0px 2px;}
#rt-header .rt-container .rt-grid-2 {margin-left:0px;}
#rt-header .rt-container .rt-grid-2 .rt-block {
	top:15px;
	left:1px;
	padding:0px;}

/* Header - 1px header hack for chrome/safari */
@media screen and (-webkit-min-device-pixel-ratio:0){
	#rt-header .rt-container {width: 959px !important;}}

/* Header - Search */
.search {float:right;margin-top:-24px;}
.search .button {position:absolute;
height: 19px;
}
#mod-search-searchword{margin:0px}

/* Showcase */
#rt-showcase {
	height:136px;}
/* Leaderboard ads */
#rt-showcase .rt-grid-2 {
        width:230px;
	position: relative;
	top: 13px;
        margin:0px;}
.ad_header_special {
        /* background: url("/images/linkbars/demo/small_ad.gif") no-repeat center bottom; */
	background: none;
	height: 110px;
	width: 220px;
	padding:0px;}
.ad_header_special .rt-block {
        padding:0px;
        margin:0px}


#rt-showcase .rt-grid-10 {
	width:728px;
	position: relative;
	top: 13px;
	margin:0px;}
.ad_header_lb_text {
	background: #202020;
	color: #cccccc;
	font: bold 12px trebuchet MS;
	padding: 0;
	height:20px;
	text-align: center;}
.ad_header_lb_text div {
	padding-top:3px;
}

.ad_header_lb {
	/* background: url("/images/linkbars/demo/leaderboard.png") no-repeat center bottom; */
	background: none;
	height:110px;
	width: 728px;
	padding:0px;
	float:right;}
.ad_header_lb .rt-block {
	padding:0px;
	margin:0px}	

/* Sidebar Pos 1 */
#rt-sidebar-a .nopadding .rt-block{
	padding:0px;
	height:250px;
        /* background: url("/images/linkbars/demo/300x250.png") no-repeat center bottom; */
	background: none;
}

#rt-sidebar-a .nopadding_bg .rt-block{
        padding:0px;
        height:250px;
        /* background: url("/images/linkbars/demo/300x250.png") no-repeat center bottom; */
        background: none;
}
 

/* Sidebar Linkbar God Sent Us */
#rt-sidebar-a .linkbar_godsent .rt-block{
        padding:0px;}
#rt-sidebar-a .module-title h2 {
	border: none;
	background: transparent;
	color: #e3e1a3 !important;
	text-transform: uppercase;
	padding-bottom:2px;
	font-family: Verdana, Verdana, Geneva, sans-serif;
	font-size:19px;
}
.linkbar_godsent {
        padding:0px;
	background:url('/templates/rt_gantry/images/body/title_bg.gif');
}
.linkbar_godsent_title {
	position:relative;
	top:-30px;}
.linkbar_godsent_text {
padding:0px;
}

#linkbar_godsent.image {
   position: relative;
   width: 100%; /* for IE 6 */
}

#linkbar_godsent h2 {
   position: absolute;
   top: 200px;
   left: 0;
   width: 100%;
}
.linkbar_bottom {
	margin-top:0px !important;
	padding-bottom:10px !important;
}
div#linkbar_godsent_text {
	position: relative;
	height: 16px;
	width: auto;
	top: 125px;
	color: #441f01;
        font-family: Verdana, Verdana, Geneva, sans-serif;
	font-weight:bold;
	font-size: 11px;
	padding: 2px 8px;
	text-align:center;
	text-transform:uppercase;
	overflow:hidden;
/*   background: rgb(0, 0, 0); /* fallback color */
/*   background: rgba(0, 0, 0, 0.7);*/
	background: url('/templates/rt_gantry/images/body/linkbar_titles.png');
}

a#linkbar_godsent_link {
	background-repeat: no-repeat;height: 145px;width: 286px;
	display:block;
	margin:3px 5px;
	border: 2px solid gray
}


.linkbar_top8 {
	padding: 10px 0 0px 10px;
	margin-top: 16px;
	padding-right:0px;
        background:url('/templates/rt_gantry/images/body/title_bg.gif');
}
.linkbar_top8 .rt-block{
	padding:0px;
	margin:0px;
}
a#linkbar_top8_link {
	background-repeat: no-repeat;
	border: 1px solid gray;
        height: 65px;
	width: 65px;
        display:block;
}


#rt-content-top {
    padding: 0px;
    position: relative;
}

/* Features */
#rt-feature .rt-container {
	height:320px;
}
#rt-feature .rt-grid-4 {
	margin:0px;
	width: 320px;}
#rt-feature .rt-block, #rt-feature .rt-grid-12 {
	padding: 0px;
	margin: 0px;
	width: 960px !important;
}
.k2ItemsBlock ul {
        list-style:none;
        padding:0px;
        diplay:block;
}
#rt-feature .k2ItemsBlock ul {
	padding-left:6px;
}
#rt-feature .k2ItemsBlock ul li {
	border: 2px solid gray;
	float: left;
	height: 200px;
	margin: 10px;
	width: 292px;
	padding: 0px;
	background: transparent;
}
#rt-feature .moduleItemIntrotext {
	padding:0px;
}
#rt-feature .moduleItemIntrotext img {
        margin:0px;
	border:0px;
	height: 200px !important;
	width: 292px !important;
	position: relative;
}
#rt-feature .moduleItemIntrotext a.moduleItemTitle {
	background: url('/templates/rt_gantry/images/body/watch.gif') white no-repeat 210px center;
    border: 2px solid gray;
    border-bottom-left-radius: 15px;
    border-bottom-right-radius: 15px;
    float: right;
    font-size: 16px;
    height: 66px;
    left: 2px;
    margin: 0 0 1px;
    padding: 4px 85px 0 5px;
    width: 202px;
    color: #264040;
        font-family: Verdana, Verdana, Geneva, sans-serif;
	text-transform: uppercase;
}
#rt-feature .moduleItemIntrotext .moduleItemIT {
        padding:0px;
        background: rgb(0, 0, 0); /* fallback color */
        background: rgba(0, 0, 0, 0.7);
        float: right;
        margin: 0;
        top: -200px;
        width: 292px;
}

/* Content */
/* .main-content:not(.k2ItemsBlock) {
	position: absolute;
	top: 224px;
	width: 620px;
} */

.main-content .rt-block {
	margin:0px 0px 0px 0px;
	padding:0px;
}

div.k2ItemsBlock ul li div.moduleItemAuthor {
    float: right;
    margin-left: 360px;
    margin-top: 106px;
    position: absolute;
    text-align: right;
    width:250px;
    color:#bbbbbb;
    font-size:90%;
    height:15px;
}
div.k2ItemsBlock ul li div.moduleItemAuthor a {
    color:#bbbbbb;
    font-size:90%;
    height:15px;
}

div.k2ItemsBlock ul li span.moduleItemDateCreated {
    float: left;
    margin-left:5px;
    color:#bbbbbb;
    font-size:90%;
    height:15px;
}

#rt-main .rt-container, #rt-feature .rt-container, #rt-bottom .rt-container {
        background:url('/templates/rt_gantry/images/body/content_bg.gif') !important;
}
#rt-content-top, #rt-content-bottom {
	float:left;
}
#rt-bottom .rt-container {
	padding-bottom: 10px;
}
.module-title h2 {
	background:url('/templates/rt_gantry/images/body/title_bg.gif');
	color: #ffff33;
	margin:0px;
	padding: 5px 10px;
	border: 2px solid gray;
	/*font-family: Tahoma;*/
	font-family: Verdana, Verdana, Geneva, sans-serif;
	font-weight: bold;
}
.main-content .module-title h2 {
	color: white;
	text-transform: uppercase;
}
.moduleItemIntrotext img {
	border: 2px solid #DDDDDD;
	float: left !important;
	height: 95px !important;
	margin: 2px 0 4px 4px;
	padding: 0;
	width: 140px !important;}
.moduleItemTitle {
	float:left;
	width:418px;
	margin:0px 10px;
	position:relative;
	font-weight:bold;
	font-size:20px;         
	font-family: Verdana, Verdana, Geneva, sans-serif;
	color: #264040;
	padding: 0;
	text-transform:uppercase;
	line-height:20px;
}
.moduleItemIT {
        float:left;
        width:418px;
        margin:0px 10px;
        position:relative;
	font:16px Verdana;
	color:gray;
        padding: 4px 0px 0px 0px;
}
.component-content {
    float: left;
    left: -15px;
    position: relative;
    width: 620px;
}
.component-content .itemTitle {
	font-family: Verdana, Verdana, Geneva, sans-serif !important;
	font-weight: bold !important;
	font-size: 20px !important;
	padding: 5px 0px 5px 7px  !important;
	color: white;
	height:auto;
	border: 2px solid gray;
	text-transform: uppercase;
        background:url('/templates/rt_gantry/images/body/title_bg.gif');
	letter-spacing: 0.1px;	
}
.linkbar_top8 .module-title h2 {
	border: none;
	text-transform: uppercase;
	color: white;
	padding: 0px 0px 8px 0px;
}

div.itemToolbar {
    height: 0;
    margin: 0;
    overflow: hidden;
    padding: 0;
    position: relative;
    width: 0;
top:-3000px;
left:-3000px;
}
span.itemImage img {
    width: 530px !important;
}
.itemFullText p {
	font-size:14px;
}
.itemImageBlock {
	padding-top: 0px !important;
}
.itemRatingBlock {
        padding-bottom: 0px !important;
}
.component-content ul.itemRatingList {
    margin: 0px 0px 10px 0px;
    padding: 0 0 0 15px;
    width: 110px;
}
/* .fb-like {
	height:33px;
        background: rgb(0, 0, 0); *//* fallback color *//*
        background: rgba(0, 0, 0, 0.7);
    bottom: 0px;
    left: 0;
    position: fixed !important;
    width: 100%;
	padding:0px 0 0 10px;
	font-size:22px;
	text-align: center;
}
.fb_ltr {
	top:5px;
}*/
#rt-showcase .rt-grid-2 .rt-block {
	margin:0px;
	padding:0px;
}
.linkbar_top_single {
	width: 300px;
	height: 100px;
background-size: 100%;
}

.widget_table {
	background: transparent !important;
}
.widget_table td {
	padding-right: 20px !important;
}
.menutop {
	top: 74px !important;
	left: 309px !important;
}
.item:hover  {
	background:none;
}
.rt-fusionmenu .nopill .rt-menubar .menutop li.item519 a.item  {
	background:url('/templates/rt_gantry/images/menus/nav_home.png') !important;
	width:64px;
	height:31px;
}
.rt-fusionmenu .nopill .rt-menubar .menutop li.item519 a.item span {
	text-indent:-5000px;
}

.rt-fusionmenu .nopill .rt-menubar .menutop li.item520 a.item  {
	background:url('/templates/rt_gantry/images/menus/nav_videos.png') !important;
	width:79px;
	height:31px;
	margin-left:4px;
}
.rt-fusionmenu .nopill .rt-menubar .menutop li.item520 a.item span {
	text-indent:-5000px;
}

.rt-fusionmenu .nopill .rt-menubar .menutop li.item521 a.item  {
	background:url('/templates/rt_gantry/images/menus/nav_photos.png') !important;
	width:86px;
	height:31px;
	margin-left:4px;
}
.rt-fusionmenu .nopill .rt-menubar .menutop li.item521 a.item span {
	text-indent:-5000px;
}

.rt-fusionmenu .nopill .rt-menubar .menutop li.item522 a.item  {
	background:url('/templates/rt_gantry/images/menus/nav_sports.png') !important;
	width:64px;
	height:31px;
	margin-left:4px;
}
.rt-fusionmenu .nopill .rt-menubar .menutop li.item522 a.item span {
	text-indent:-5000px;
}

.rt-fusionmenu .nopill .rt-menubar .menutop li.item524 a.item  {
	background:url('/templates/rt_gantry/images/menus/nav_celebs.png') !important;
	width:86px;
	height:31px;
	margin-left:4px;
}
.rt-fusionmenu .nopill .rt-menubar .menutop li.item524 a.item span {
	text-indent:-5000px;
}

.rt-fusionmenu .nopill .rt-menubar .menutop li.item523 a.item  {
	background:url('/templates/rt_gantry/images/menus/nav_gaming.png') !important;
	width:79px;
	height:31px;
	margin-left:4px;
}
.rt-fusionmenu .nopill .rt-menubar .menutop li.item523 a.item span {
	text-indent:-5000px;
}
.sharepoint {
	float:right;
	margin-right:25px;
}
.linkbar_nailed_it {
        padding:0px;
}

.linkbar_nailed_it_title {
	position:relative;
	top:-30px;
}
.linkbar_nailed_it .rt-block {
	padding:0px;
}
#rt-sidebar-a .linkbar_nailed_it .rt-block .module-title h2 {
	background:url('/templates/rt_gantry/images/body/title_bg.gif');
	border: 2px solid gray;
	padding-bottom:4px;
}
.linkbar_nailed_it .even, .linkbar_nailed_it .odd {
	background: Gainsboro;
	padding:0px;
	border: 2px solid lightgray;
	margin: 10px 2px 0px 2px;
}
.linkbar_nailed_it .even .moduleItemIntrotext img, .linkbar_nailed_it .odd .moduleItemIntrotext img{
	margin: 0px !important;
}
.linkbar_nailed_it .moduleItemIntrotext {
	margin: 0px !important;
	padding: 0px !important;
}
.linkbar_nailed_it .moduleItemTitle {
	float:right;
	width:142px;
	margin: 3px !important;
	padding: 0px !important;
	font-size:15px !important;
	line-height:18px;
}
.copytext {
	width: 100%;
	text-align:center;
	color:silver;
}
#rt-header .rt-container .rt-grid-10 {
	height:10px;
}
#rt-header .rt-container .rt-grid-10 .rt-block {
	float:right;
	width:200px;
}
.footer .rt-block, .contact .rt-block {
	height:15px;
	margin: 0;
	padding:10px 0 15px 0;
}
.menucontact_menu .rt-block {
	height 15px;
	margin: 0;
	padding: 10px 0 0 0;
}
ul.menucontact_menu {
	display: inline-block;
	margin-left:342px;
}

.menucontact_menu li {
    height: auto;
    list-style: none outside none;
    margin: 0;
    padding: 5px;
    padding-right:0px;
    position: relative;
    font-family: Helvetica,Arial,FreeSans,sans-serif;
    float:left;
    color:silver;
    line-height:1.7em;
}
.menucontact_menu li:after {content:" | "}
.menucontact_menu li:last-child:after {content: ""}
.menucontact_menu a {
	color:silver;
	font-size: 1.1em;
}

.latestItemsContainer .latestItemsCategory h2 {
	margin: 20px 0 0 0;
	padding:0px;
	height: 0px;
}
.latestItemsContainer .latestItemsCategory h2 a {
    color: white;
    text-transform: uppercase;
    border: 2px solid gray;
    background: url("/templates/rt_gantry/images/body/title_bg.gif") repeat scroll 0 0 transparent;
    font-family: Verdana,Verdana,Geneva,sans-serif;
    font-weight: bold;
    margin: 0;
    padding: 5px 10px;
    display: block;
    width: 596px;
}
div.k2FeedIcon {
    position: absolute;
    top: 7px;
    left: 582px;
}
div.latestItemsCategory {
    background: none repeat scroll 0 0 transparent;
    border: 0 none;
    margin: 0;
    padding: 0;
}

.dont_push {
	background:url('/templates/rt_gantry/images/body/dont_push.gif');
	width:60px;
	height:60px;
	float:right;
	position:absolute;
	margin-left:975px;
	top:-79px;
	left:1px;
}
.dont_push_container {
	float:left;
	position:absolute;
}
.itp-sharepoint-fbl {
    float: left;
    margin-right: 14px;
    margin-top: 10px;
	width:60px;
}
.itp-sharepoint-digg {
    float: left;
    margin: 10px;
}

#rt-logo {background: url(/templates/rt_gantry/images/logo/logo.png) 0 0 no-repeat;width: 189px;height: 115px;display: block;}
/**
 * @package   Gantry Template - RocketTheme
 * @version   3.2.12 October 30, 2011
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

/* Global */
.menutop li {height: auto;margin: 0;padding: 0;position: relative;list-style: none;}
.menutop em {font-size: 80%;font-weight: normal;display: block;font-style: normal;}
.menutop li .item, .menutop li.active .item {display: block;margin: 0;text-decoration: none;float: none;width: auto;}
.menutop li .fusion-submenu-wrapper {float: none;left: -999em;position: absolute;z-index: 500;}
.menutop li:hover li ul, .menutop li.sfHover li ul {top: -999em;}
.menutop li:hover ul, .menutop li.sfHover ul {top: 0;}

/* Root Items */
.menutop {list-style: none;margin: 0;padding: 0 10px;position: relative;line-height: 1em;display: inline-block;}
.menutop li.root {float: left;}
.menutop li.root > .item {white-space: nowrap;display: block;padding: 0;font-size: 1em;z-index: 100;cursor: pointer;position: relative;margin: 0;outline: none;height: 3em;}
.menutop li.root > .item span {display: block;margin: 0;outline: none;padding: 0 15px;width: auto;line-height: 3em;}
.menutop li.root > .item em {font-size: 10px;display: block;text-transform: lowercase;line-height: 0.3em;}
.menutop li.parent.root .item span {padding-right: 20px;}
.menutop li.root .subtext span {line-height: 1.9em;}
.menutop li.root > .item img {margin: 0 4px 0 0;vertical-align: text-bottom;}

/* Dropdown Surrounds */
.menutop ul {padding: 0;margin: 0;float: left;}
.menutop .drop-bot {height: 1px;overflow: hidden;clear: both;}
.menutop .fusion-submenu-wrapper {height: auto !important;}

/* Dropdown Items */
.menutop ul li {padding: 0;display: block;}
.menutop ul li > .item {padding: 0 15px;height: auto;display: block;font-size: 1em;cursor: pointer;}
.menutop ul li > .item span {display: block;width: 100%;overflow: hidden;line-height: 3em;}
.menutop ul li .item img {float: left;margin: 8px 6px 0 0;vertical-align: top;}
.menutop ul li .nolink span {display: block;}
.menutop ul li span.item {cursor: default;outline: none;}
.menutop ul li .subtext span {line-height: 1.9em;}
.menutop ul li .subtext em {line-height: 0.6em;padding-bottom: 7px;text-transform: lowercase;}

/* No JS */
.menutop li.root:hover > .fusion-submenu-wrapper {top: 35px;left: 0;}
.menutop ul li:hover > .fusion-submenu-wrapper {left: 180px;top: 0;}

/* Fusion JS */
.fusion-js-container {display: block;height: 0;left: 0;overflow: visible;position: absolute;top: 0;z-index: 600000!important;background: transparent !important;}
.fusion-js-subs {display: none;margin: 0;overflow: hidden;padding: 0;position: absolute;}

/* Grouped & Modules */
.menutop .fusion-grouped {padding-bottom: 10px;}
.menutop .fusion-grouped ol {padding: 0;}
.menutop .fusion-grouped ol li {padding: 0 15px;}
.menutop .fusion-grouped ol li .item {padding: 0 15px;}
.menutop .fusion-grouped ol li span {font-size: 85%;line-height: 2em;}
.menutop .type-module ol {padding: 0;}
.menutop .type-module ol li {padding: 0;}
.menutop .type-module ol li .fusion-modules {background: none;}
.menutop .type-module ol li .fusion-module {padding: 0;background: none;overflow: hidden;}
.menutop .fusion-module, .menutop .fusion-modules, .menutop .fusion-grouped {display: block;}
.menutop .fusion-modules.item {padding: 15px;}
.menutop .fusion-module em {display: inline;font-size: inherit;font-style: italic;}
.menutop .fusion-module a {font-size: inherit;line-height: 130%;}
.menutop .fusion-module p, .menutop .fusion-modules p {line-height: 160%;}
.menutop ul li.grouped-parent > .daddy span {background: none;}

/* Main Menu */
.menutop {
	top:68px;
	left:346px;}

/*
	Copyright (C) 2009,2010  Monev Software LLC.

	All Rights Reserved.
	
	http://www-joomlaxtc.com
*/

.popuphover{
  cursor:pointer;
}

.jxtcpopup{
  position:absolute;
  float:left;
  top:0;
  left:0;
  margin:0px auto 0px auto;
  padding:5px;
  z-index:99999;
  border:8px solid #d1d1d1;
  background: #d1d1d1;
}

.jxtcinner{
  width:auto;
}

.jxtcpopupclose{
  position:absolute;
  width:30px;
  height:30px;
  margin:0;
  padding:0px;
  top:-25px;
  right:0px;
  cursor:pointer;
  background: url(/modules/mod_jxtc_weblinksplus/images/closebox.png) no-repeat;
}

.jxtcpopupdrag{
  position:absolute;
  width:30px;
  height:30px;
  margin:0;
  padding:0px;
  top:-25px;
  right:35px;
  cursor:pointer;
  background: url(/modules/mod_jxtc_weblinksplus/images/movebox.png) no-repeat;
}

.tip{
	display:none;
}

.pop{
	display:none;
	cursor:pointer;
}
";}