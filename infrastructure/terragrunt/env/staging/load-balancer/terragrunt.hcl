include {
  path = find_in_parent_folders()
}

dependencies {
  paths = ["../network", "../hosted-zone", "../storage"]
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

dependency "storage" {
  config_path = "../storage"

  mock_outputs_allowed_terraform_commands = ["init", "fmt", "validate", "plan", "show"]
  mock_outputs = {
    s3_bucket_regional_domain_name = ""
  }  
}

inputs = {
  domain_name                                  = "articles.cdssandbox.xyz"
  load_balancer_security_group_id              = dependency.network.outputs.load_balancer_security_group_id
  public_subnet_ids                            = dependency.network.outputs.public_subnet_ids
  vpc_id                                       = dependency.network.outputs.vpc_id
  zone_id                                      = dependency.hosted-zone.outputs.zone_id
  s3_bucket_regional_domain_name               = dependency.storage.outputs.s3_bucket_regional_domain_name
  s3_cloudfront_origin_access_identity_iam_arn = dependency.storage.outputs.s3_cloudfront_origin_access_identity_iam_arn
}

terraform {
  source = "../../../aws//load-balancer"
}
