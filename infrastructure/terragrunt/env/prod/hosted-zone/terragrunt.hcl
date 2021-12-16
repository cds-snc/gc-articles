include {
  path = find_in_parent_folders()
}

inputs = {
  zone_name = "articles.alpha.canada.ca"
}

terraform {
  source = "git::https://github.com/cds-snc/gc-articles//infrastructure/terragrunt/aws/hosted-zone?ref=${get_env("TARGET_VERSION")}"
}
