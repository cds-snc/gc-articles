include {
  path = find_in_parent_folders()
}

inputs = {
  zone_name = "platform-ircc.cdssandbox.xyz"
}

terraform {
  source = "../../../aws//hosted-zone"
}
