<?php
    /**
     * GIT DEPLOYMENT SCRIPT
     *
     * Used for automatically deploying websites via github or bitbucket, more deets here:
     * https://gist.github.com/riodw/71f6e2244534deae652962b32b7454e2
     * How To Use:
     * https://medium.com/riow/deploy-to-production-server-with-git-using-php-ab69b13f78ad
     *
     * Slightly changed by Patrick Scholz ;-)
     * https://github.com/patrick-scholz
     *
     */
	// The commands
	$commands = array(
		'echo $PWD',
		'whoami',
		'git reset --hard HEAD',
		'git pull',
		'git status',
		'git submodule sync',
		'git submodule update',
		'git submodule status',
		'php ../bin/console doctrine:migrations:migrate',
		'composer install',
		'php ../bin/console cache:clear'
	);
	// Run the commands for output
	$output = '';
	foreach($commands AS $command){
		// Run it
		$tmp = shell_exec($command);
		// Output
		$output .= "<span style=\"color: #6BE234;\">\$</span> <span style=\"color: #729FCF;\">{$command}\n</span>";
		$output .= htmlentities(trim($tmp)) . "\n";
	}

    /**
     * Set deployment timestamp into twig config to refresh user cache for css/js/[…] files.
     */
    $file = '../config/packages/twig.yaml';
    $config = yaml_parse(file_get_contents($file));
    $config['twig']['globals']['lastDeploy'] = time();
    $config = yaml_emit($config);
    file_put_contents($file, $config);

?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>GIT DEPLOYMENT SCRIPT</title>
</head>
<body style="background-color: #000000; color: #FFFFFF; font-weight: bold; padding: 0 10px;">
<pre>
 _______________________
|                       |
| Git Deployment Script |
|_______________________|

<?php echo $output; ?>
</pre>
</body>
</html>
