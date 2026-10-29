[CmdletBinding()]
param(
    [Parameter(ValueFromRemainingArguments = $true)]
    [string[]] $ComposerArguments
)

$ErrorActionPreference = 'Stop'
$projectRoot = (Resolve-Path (Join-Path $PSScriptRoot '..\..')).Path
$phpWrapper = Join-Path $PSScriptRoot 'php.ps1'
$composerPhar = Join-Path $projectRoot '.local\runtime\composer\composer.phar'

if (-not (Test-Path -LiteralPath $composerPhar -PathType Leaf)) {
    throw 'Composer lokal belum tersedia di .local/runtime/composer.'
}

& $phpWrapper $composerPhar @ComposerArguments
exit $LASTEXITCODE
