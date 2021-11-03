include {
  path = find_in_parent_folders()
}

dependencies {
  paths = ["../network", "../hosted-zone"]
}

dependency "network" {
  config_path = "../network"

  mock_outputs_allowed_terraform_commands = ["init", "fmt", "validate", "plan", "show"]
  mock_outputs = {
    load_balancer_security_group_id = ""
    public_subnet_ids               = [""]
    vpc_id                          = ""
  }
}

dependency "hosted-zone" {
  config_path = "../hosted-zone"

  mock_outputs_allowed_terraform_commands = ["init", "fmt", "validate", "plan", "show"]
  mock_outputs = {
    zone_id = ""
  }
}

inputs = {
  allow_wordpress_uploads         = true
  domain_name                     = "articles.cdssandbox.xyz"
  load_balancer_security_group_id = dependency.network.outputs.load_balancer_security_group_id
  public_subnet_ids               = dependency.network.outputs.public_subnet_ids
  vpc_id                          = dependency.network.outputs.vpc_id
  zone_id                         = dependency.hosted-zone.outputs.zone_id
}

terraform {
  source = "../../../aws//load-balancer"
}
