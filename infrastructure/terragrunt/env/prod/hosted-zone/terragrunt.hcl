include {
  path = find_in_parent_folders()
}

inputs = {
  zone_name = "ircc.digital.canada.ca"
}

terraform {
  source = "../../../aws//hosted-zone"
}
