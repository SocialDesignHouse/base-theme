		<footer id="footer">
		</footer>
		
		<?php wp_footer(); ?>

		<!-- Asynchronous Twitter, Google+, and Google Analytics scripts below if needed -->
		
		<!-- Twitter -->
		<script type="text/javascript">
			// <![CDATA[
			(function() {
				var twitterScriptTag = document.createElement('script');
				twitterScriptTag.type = 'text/javascript';
				twitterScriptTag.async = true;
				twitterScriptTag.src = 'http://platform.twitter.com/widgets.js';
				var s = document.getElementsByTagName('script')[0];
				s.parentNode.insertBefore(twitterScriptTag, s);
			})();
			// ]]>
		</script>
		<!-- Google+ -->
		<script type="text/javascript">
			// <![CDATA[
			(function() {
				var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
				po.src = 'https://apis.google.com/js/plusone.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			})();
			// ]]>
		</script>
		<!-- Google Analytics -->
		<script type="text/javascript">
			// <![CDATA[
			var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
			document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
			// ]]>
		</script>
		<script type="text/javascript">
			// <![CDATA[
			var ga_ua = ''; //fill this in to add tracking
			try {
				var pageTracker = _gat._getTracker(ga_ua);
				pageTracker._trackPageview();
			} catch(err) {}
			// ]]>
		</script>

		<script src="<?php bloginfo('template_directory'); ?>/_/js/functions.js"></script>	

	</body>
</html>