locals {
  vars = read_terragrunt_config("../env_vars.hcl")
}

inputs = {
  account_id                = "${local.vars.inputs.account_id}"
  billing_tag_key           = "${local.vars.inputs.billing_tag_key}"
  billing_tag_value         = "${local.vars.inputs.billing_tag_value}"
  enable_efs                = "${local.vars.inputs.enable_efs}"
  env                       = "${local.vars.inputs.env}"
  region                    = "ca-central-1"
  cbs_satellite_bucket_name = "cbs-satellite-${local.vars.inputs.account_id}"
}

remote_state {
  backend = "s3"
  generate = {
    path      = "backend.tf"
    if_exists = "overwrite_terragrunt"
  }
  config = {
    encrypt        = true
    bucket         = "platform-mvp-articles-${local.vars.inputs.env}-tfstate"
    dynamodb_table = "terraform-state-lock-dynamo"
    region         = "ca-central-1"
    key            = "${path_relative_to_include()}/terraform.tfstate"
  }
}

generate "provider" {
  path      = "provider.tf"
  if_exists = "overwrite"
  contents  = file("./common/provider.tf")
}

generate "common_variables" {
  path      = "common_variables.tf"
  if_exists = "overwrite"
  contents  = file("./common/common_variables.tf")
}
