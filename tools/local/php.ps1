[CmdletBinding()]
param(
    [Parameter(ValueFromRemainingArguments = $true)]
    [string[]] $PhpArguments
)

$ErrorActionPreference = 'Stop'
$projectRoot = (Resolve-Path (Join-Path $PSScriptRoot '..\..')).Path
$phpExecutable = Join-Path $projectRoot '.local\runtime\php\php.exe'
$phpIni = Join-Path $projectRoot '.local\runtime\php\php.ini'

if (-not (Test-Path -LiteralPath $phpExecutable -PathType Leaf)) {
    throw 'Runtime PHP lokal belum tersedia di .local/runtime/php.'
}

if (-not (Test-Path -LiteralPath $phpIni -PathType Leaf)) {
    throw 'php.ini lokal belum tersedia. Salin tools/runtime/php.ini terlebih dahulu.'
}

& $phpExecutable -c $phpIni @PhpArguments
exit $LASTEXITCODE
