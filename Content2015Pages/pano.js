window.onload = loadPredefinedPanorama;

// Load the predefined panorama
function loadPredefinedPanorama() {

	// Loader
	var loader = document.createElement('div');
	loader.className = 'loader';

	// Panorama display
	var Beforediv = document.getElementById('beforePano');
	Beforediv.style.height = '30px';

	var PSV = new PhotoSphereViewer({
		// Path to the panorama
		panorama: 'Content2015Before.jpg',

		// Container
		container: Beforediv,

		// Deactivate the animation
		time_anim: false,

		// Display the navigation bar
		navbar: true,

		// Resize the panorama
		size: {
			width: '100%',
			height: '500px'
		},

		// HTML loader
		loading_html: loader,

		// Slower movements
		long_offset: Math.PI / 720,
		lat_offset: Math.PI / 360
	});

	var ReadyPSV = new PhotoSphereViewer({
		// Path to the panorama
		panorama: 'ReadyToGoPano.jpg',

		// Container
		container: SetupPano,

		// Deactivate the animation
		time_anim: false,

		// Display the navigation bar
		navbar: true,

		// Resize the panorama
		size: {
			width: '100%',
			height: '500px'
		},

		// HTML loader
		loading_html: loader,

		// Slower movements
		long_offset: Math.PI / 720,
		lat_offset: Math.PI / 360
	});
}

// Load a panorama stored on the user's computer
function upload() {
	// Retrieve the chosen file and create the FileReader object
	var file = document.getElementById('pano').files[0];
	var reader = new FileReader();

	reader.onload = function() {
		var div = document.getElementById('your-pano');

		var PSV = new PhotoSphereViewer({
			// Panorama, given in base 64
			panorama: reader.result,

			// Container
			container: div,

			// Deactivate the animation
			time_anim: false,

			// Display the navigation bar
			navbar: true,

			// Resize the panorama
			size: {
				width: '100%',
				height: '500px'
			},

			// No XMP data
			usexmpdata: false
		});
	};

	reader.readAsDataURL(file);
}
