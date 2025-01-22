include {
  path = find_in_parent_folders("root.hcl")
}

terraform {
  source = "git::https://github.com/cds-snc/gc-articles//infrastructure/terragrunt/aws/network?ref=${get_env("TARGET_VERSION")}"
}
