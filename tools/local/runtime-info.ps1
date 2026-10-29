[CmdletBinding()]
param()

$ErrorActionPreference = 'Stop'
$phpWrapper = Join-Path $PSScriptRoot 'php.ps1'
$probe = @'
echo json_encode([
    'php_version' => PHP_VERSION,
    'sapi' => PHP_SAPI,
    'session_upload_progress_enabled' => filter_var(
        ini_get('session.upload_progress.enabled'),
        FILTER_VALIDATE_BOOLEAN
    ),
], JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
'@

$probeArguments = @('-r', $probe)
& $phpWrapper -PhpArguments $probeArguments
exit $LASTEXITCODE
