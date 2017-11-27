Vagrant.require_version ">= 2.0.0"

environment_name = "SimplePie NG Development Environment"
memsize = 2048
numvcpus = 2
hostname_aliases = %w(simplepie-ng.local)

Vagrant.configure("2") do | config |

  # Box
  config.vm.box = "skyzyx/centos7"
  config.vm.boot_timeout = 240

  # Networking
  config.vm.hostname = "simplepie-ng"
  config.vm.network :private_network, ip: "33.33.33.10"
  config.ssh.forward_agent = true

  # Manage hosts, if possible
  if Vagrant.has_plugin?("vagrant-hostmanager")
    config.hostmanager.enabled = true
    config.hostmanager.manage_host = true
    config.hostmanager.manage_guest = true
    config.hostmanager.ignore_private_ip = false
    config.hostmanager.include_offline = true
  end

  # Allow 20 seconds to gracefully halt (instead of 60)
  config.vm.graceful_halt_timeout = 20

  # Cache the yum packages locally if we can
  if Vagrant.has_plugin?("vagrant-cachier")
    config.cache.scope = :machine
    config.cache.auto_detect = true
    config.cache.enable :yum
  end

  # Check for vbguest plugin
  if Vagrant.has_plugin?("vagrant-vbguest")
    config.vbguest.auto_update = true
    config.vbguest.no_remote = false
  end

  # Synced folders
  if Vagrant::Util::Platform.windows?
    config.vm.communicator = "winrm"
    config.vm.synced_folder "", "/vagrant", type: "smb"
    config.vm.synced_folder (File.expand_path '~'), "/home/vagrant/host", type: "smb"
  else
    config.vm.synced_folder "", "/vagrant", type: "nfs"
    config.vm.synced_folder (File.expand_path '~'), "/home/vagrant/host", type: "nfs",  mount_options: ['rw', 'vers=3', 'tcp', 'fsc', 'actimeo=2']
    # If using Sublime Text on the Mac side, edit your preferences: https://www.jverdeyen.be/vagrant/speedup-vagrant-nfs/
  end

  # Oracle VirtualBox
  config.vm.provider :virtualbox do | vb |
    vb.name = environment_name
    vb.gui = false
    vb.linked_clone = true

    vb.memory = memsize
    vb.cpus = numvcpus
    vb.customize ["modifyvm", :id, "--ioapic", "on"]

    # if Vagrant.has_plugin?("vagrant-hostmanager")
    #   vb.hostmanager.aliases = hostname_aliases
    # end
  end

  # VMware Fusion
  config.vm.provider :vmware_fusion do | vm |
    vm.name = environment_name
    vm.gui = false
    vm.functional_hgfs = false
    vm.linked_clone = true

    vm.vmx["memsize"] = memsize
    vm.vmx["numvcpus"] = numvcpus

    if Vagrant.has_plugin?("vagrant-hostmanager")
      vm.hostmanager.aliases = hostname_aliases
    end
  end

  # Parallels Desktop
  config.vm.provider :parallels do | prl |
    prl.name = environment_name
    prl.update_guest_tools = true
    prl.linked_clone = true

    prl.memory = memsize
    prl.cpus = numvcpus

    if Vagrant.has_plugin?("vagrant-hostmanager")
      prl.hostmanager.aliases = hostname_aliases
    end
  end

  config.vm.provision :ansible do | ansible |
    ansible.playbook = "ansible/vm.yml"
    ansible.inventory_path = "ansible/inventories/dev-vm"
    ansible.limit = "vagrant"
    ansible.verbose = false
  end

  config.vm.post_up_message = "Welcome to the SimplePie NG Development Environment."
end
