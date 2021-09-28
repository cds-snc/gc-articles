include {
  path = find_in_parent_folders()
}

inputs = {
  zone_name = "articles.cdssandbox.xyz"
}

terraform {
  source = "../../../aws//hosted-zone"
}
