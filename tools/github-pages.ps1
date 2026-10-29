[CmdletBinding()]
param([ValidateSet('Inspect', 'Enable', 'Status')][string] $Action = 'Inspect')
$ErrorActionPreference = 'Stop'
$credentialText = "protocol=https`nhost=github.com`n`n" | git -c safe.directory=D:/FreeWork/web_yayasan2 credential fill
$credentialFields = @{}
foreach ($line in $credentialText) {
    if ($line -match '^([^=]+)=(.*)$') { $credentialFields[$Matches[1]] = $Matches[2] }
}
if (-not $credentialFields['password']) { throw 'GitHub credential unavailable.' }
$headers = @{ Authorization = 'Bearer ' + $credentialFields['password']; Accept = 'application/vnd.github+json'; 'User-Agent' = 'Hope-Charity-Preview'; 'X-GitHub-Api-Version' = '2022-11-28' }
$repoUrl = 'https://api.github.com/repos/Ikbar1/hope_charity'
try {
    if ($Action -eq 'Inspect') {
        $repo = Invoke-RestMethod -Uri $repoUrl -Headers $headers
        $repo | Select-Object full_name, private, has_pages, permissions | ConvertTo-Json -Depth 3
    } elseif ($Action -eq 'Enable') {
        $body = @{ source = @{ branch = 'gh-pages'; path = '/' }; build_type = 'legacy' } | ConvertTo-Json
        try {
            $page = Invoke-RestMethod -Method Post -Uri ($repoUrl + '/pages') -Headers $headers -ContentType 'application/json' -Body $body
        } catch {
            if ([int]$_.Exception.Response.StatusCode -ne 409) { throw }
            $page = Invoke-RestMethod -Uri ($repoUrl + '/pages') -Headers $headers
            if ($page.source.branch -ne 'gh-pages' -or $page.source.path -ne '/') { throw 'Pages already configured with another source.' }
        }
        $page | Select-Object html_url, status, source | ConvertTo-Json -Depth 3
    } else {
        $page = Invoke-RestMethod -Uri ($repoUrl + '/pages') -Headers $headers
        $page | Select-Object html_url, status, source | ConvertTo-Json -Depth 3
    }
} finally {
    $credentialText = $null
    $credentialFields.Clear()
    $headers.Clear()
}
