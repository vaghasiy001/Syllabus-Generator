/* Initialize WOW.js
=============================== */ 
new WOW().init();


/* Load content when user clicks on link
=============================== */
$(function loadContent() {
	// Store content nodes in DOM
	var $siteContent = $('.site-content');
	var $aboutContent = $('.about-content');
	var $topicsContent = $('.topics-content');
	var $teachingFormatContent = $('.teaching-format-content');
	var $professorScheduleContent = $('.professor-schedule-content');
	var $policyContent = $('.policy-content');

	// Detach all content nodes that aren't the "About Content"
	$topicsContent.detach();
	$teachingFormatContent.detach();
	$policyContent.detach();
	$professorScheduleContent.detach();

	// When user clicks "Topics", node gets appended to parent
	$('.nav-topics-link').on("click", function() {
		// Empty current html inside site content
		$siteContent.empty();
		$siteContent.append($topicsContent);
	});

	// When user clicks "Format", node gets appended to parent
	$('.nav-format-link').on("click", function() {
		// Empty current html inside site content
		$siteContent.empty();
		$siteContent.append($teachingFormatContent);
	});

	// When user clicks "About", node gets appended to parent
	$('.nav-about-link').on("click", function() {
		// Empty current html inside site content
		$siteContent.empty();
		$siteContent.append($aboutContent);
	});

	// When user clicks "Policy", node gets appended to parent
	$('.nav-policy-link').on("click", function() {
		// Empty current html inside site content
		$siteContent.empty();
		$siteContent.append($policyContent);
	});

	// When user clicks "Professor", node gets appended to parent
	$('.nav-professor-link').on("click", function() {
		// Empty current html inside site content
		$siteContent.empty();
		$siteContent.append($professorScheduleContent);
	});

});

/* Mobile navigation menu
=============================== */

$(function responsiveMenu() {
	// Store content nodes in DOM
	var $menuIcon = $('.nav-bottom-mobile-menu .fa');
	var $menuList = $('.nav-bottom-mobile-menu ul');

	// Hide menu links by default
	$menuList.hide();

	// Toggle menu with icon
	$menuIcon.on("click", function(){
		$menuIcon.toggle();
		$menuList.slideToggle(300);
	});

});











