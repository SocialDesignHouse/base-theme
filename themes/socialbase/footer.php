		<footer id="footer">
		</footer>
		
		<?php wp_footer(); ?>

		<!-- Asynchronous Twitter, Google+, and Google Analytics scripts below if needed -->
		
		<!-- Type Kit -->
		<script type="text/javascript">
		(function() {
			var config = {
				//change the kitId below to the correct typekit id
				kitId : 'fvq8pye',
				scriptTimeout : 3000
			};
			var h=document.getElementsByTagName("html")[0];h.className+=" wf-loading";var t=setTimeout(function(){h.className=h.className.replace(/(\s|^)wf-loading(\s|$)/g," ");h.className+=" wf-inactive"},config.scriptTimeout);var tk=document.createElement("script"),d=false;tk.src='//use.typekit.net/'+config.kitId+'.js';tk.type="text/javascript";tk.async="true";tk.onload=tk.onreadystatechange=function(){var a=this.readyState;if(d||a&&a!="complete"&&a!="loaded")return;d=true;clearTimeout(t);try{Typekit.load(config)}catch(b){}};var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(tk,s)
		})();
	</script>
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