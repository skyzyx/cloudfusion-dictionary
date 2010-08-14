# CloudFusion Dictionary for Mac OS X

By Ryan Parman

* Portions (c) 2006-2010 Ryan Parman & Foleeo Inc.
* Portions (c) 2010 Amazon.com Inc.
* Portions (c) 2009 Mika Tuupola

Dictionary generation code based on [jQuery Dictionary](http://github.com/tuupola/jquery_dictionary) by Mika Tuupola.

Dictionary file follows the same licensing terms as the CloudFusion documentation.


## If you want to simply install...

Pull the code down from GitHub.

	cd ~/Desktop
	git clone git://github.com/skyzyx/cloudfusion-dictionary.git

...or...

	cd ~/Desktop
	svn export http://svn.github.com/skyzyx/cloudfusion-dictionary.git cloudfusion-dictionary

Open the `cloudfusion-dictionary` folder on your desktop, and copy the `CloudFusion.dictionary` dictionary file to `~/Library/Dictionaries`.


## If you want to build from source...

You must have the latest [Xcode Developer Tools](http://developer.apple.com/technologies/xcode.html) from Apple installed.

	git clone git://github.com/skyzyx/cloudfusion-dictionary.git
	cd cloudfusion-dictionary/src
	make && make install

...or...

	svn co http://svn.github.com/skyzyx/cloudfusion-dictionary.git cloudfusion-dictionary
	cd cloudfusion-dictionary/src
	make && make install


## Enable the Dictionary

1. Launch Dictionary.app
2. If you don't see "CloudFusion" in the toolbar, open Preferences [Dictionary > Preferences].
3. Enable the "CloudFusion" dictionary.
