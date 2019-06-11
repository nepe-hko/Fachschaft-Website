( function( api ) {

	// Extends our custom "xblog-pro" section.
	api.sectionConstructor['blog-bank'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );
