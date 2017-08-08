<?php

$version = $_ENV['DEPLOYER_VERSION'];

echo "Downloading Deployer manifest file...\n";

$manifest = json_decode(file_get_contents('https://deployer.org/manifest.json'), true);
$sha1 = $url = null;

foreach ($manifest as $manifestEntry)
{
    if ($manifestEntry['version'] == $version)
    {
        $sha1 = $manifestEntry['sha1'];
        $url = $manifestEntry['url'];
        break;
    }
}

if (null == $sha1)
{
    throw new Exception("Deployer version `$version` not found in manifest.");
}

echo "Downloading Deployer version $version...\n";
file_put_contents("/bin/dep", fopen($url, 'r'));

echo "Checking downloaded file...\n";

if (sha1_file("/bin/dep") != $sha1)
{
    throw new Exception("Deployer download seems to be corrupt.");
}

echo "Making Deployer writeable...\n";

exec('chmod +x /bin/dep');

echo "Deployer installed.\n";