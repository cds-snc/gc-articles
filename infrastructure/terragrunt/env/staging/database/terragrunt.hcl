include {
  path = find_in_parent_folders()
}

dependencies {
  paths = ["../network"]
}

dependency "network" {
  config_path = "../network"

  mock_outputs_allowed_terraform_commands = ["init", "fmt", "validate", "plan", "show"]
  mock_outputs_merge_with_state           = true
  mock_outputs = {
    private_subnet_ids = [""]
    vpc_id             = ""
  }
}

inputs = {
  database_instances_count              = 2
  database_instance_class               = "db.t3.small"
  database_performance_insights_enabled = false
  private_subnet_ids                    = dependency.network.outputs.private_subnet_ids
  vpc_id                                = dependency.network.outputs.vpc_id
}

terraform {
  source = "../../../aws//database"
}
