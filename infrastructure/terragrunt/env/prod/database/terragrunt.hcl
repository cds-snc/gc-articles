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
  database_instances_count = 3
  private_subnet_ids       = dependency.network.outputs.private_subnet_ids
  vpc_id                   = dependency.network.outputs.vpc_id
}

terraform {
  source = "git::https://github.com/cds-snc/gc-articles/infrastructure/terragrunt//aws/database?ref=${get_env("TARGET_VERSION")}"
}
