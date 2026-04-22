module "waf_ip_blocklist" {
  source                      = "github.com/cds-snc/terraform-modules//waf_ip_blocklist?ref=14bc5c8e2493911e3088ea9c86fd556dca8b8f5d"
  service_name                = "gc-articles-waf-ip-blocklist-test"
  athena_query_results_bucket = module.athena_bucket.s3_bucket_id
  athena_query_source_bucket  = var.cbs_satellite_bucket_name
  billing_tag_value           = var.billing_tag_value
}