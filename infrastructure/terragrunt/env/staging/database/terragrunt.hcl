include {
  path = find_in_parent_folders("root.hcl")
}

dependencies {
  paths = ["../network"]
}

dependency "network" {
  config_path = "../network"

  mock_outputs_allowed_terraform_commands = ["init", "fmt", "validate", "plan", "show"]
  mock_outputs_merge_with_state           = true
  mock_outputs = {
    client_vpn_security_group_id = "sg-0123456789101212"
    private_subnet_ids           = [""]
    vpc_id                       = ""
  }
}

inputs = {
  database_instances_count              = 1
  database_instance_class               = "db.t4g.medium"
  database_performance_insights_enabled = false
  client_vpn_security_group_id          = dependency.network.outputs.client_vpn_security_group_id
  private_subnet_ids                    = dependency.network.outputs.private_subnet_ids
  vpc_id                                = dependency.network.outputs.vpc_id
}

terraform {
  source = "../../../aws//database"
}
