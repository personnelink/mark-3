' vbscript to create and add generic ubuntu linux guest to hyper-v for hosting docker core from dev-server
$VMName = 'docker-core'
$VHDName = '{0}.vhdx' -f $VMName
$VMStoragePath = '\\localhost\vms'
$VHDStoragePath = Join-Path -Path '\\svstore\vms\vhds' -ChildPath $VHDName
$UbuntuISOPath = '\\localhost\isos\ubuntu-16.04-server-amd64.iso'
 
New-VM -Name $VMName -MemoryStartupBytes 512MB -SwitchName vSwitch -BootDevice CD -Path $VMStoragePath -Generation 1 -NoVHD
Set-VMMemory -VMName $VMName -DynamicMemoryEnabled $true -MinimumBytes 256MB -MaximumBytes 1GB
Set-VMProcessor -VMName $VMName -Count 2
New-VHD -Path $VHDStoragePath -SizeBytes 40GB -Dynamic -BlockSizeBytes 1MB
Add-VMHardDiskDrive -VMName $VMName -ControllerType IDE -ControllerNumber 0 -ControllerLocation 0 -Path $VHDStoragePath
Set-VMDvdDrive -VMName $VMName -ControllerNumber 1 -ControllerLocation 0 -Path $UbuntuISOPath
Add-ClusterVirtualMachineRole -VMName $VMName