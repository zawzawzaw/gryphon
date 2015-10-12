jQuery(document).ready(function($) {

	var feed = new Instafeed({
        get: 'user',
        userId: 637296152,        
        accessToken: '637296152.467ede5.9bae6b7506ad4287b4ba40e25edcc7b0',
        limit: 4
    });
    feed.run();

});