jQuery(document).ready(function($) {

	var feed = new Instafeed({
        get: 'tagged',
        tagName: 'gryphontea',
        clientId: '7ff984bc8161403aa84f7c52facc0aa6',
        limit: 4
    });
    feed.run();

});