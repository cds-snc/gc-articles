#
# Create Athena queries to view the WAF and load balancer access logs
#
module "athena_access_logs" {
  source = "github.com/cds-snc/terraform-modules//athena_access_logs?ref=v10.6.2"

  athena_bucket_name = module.athena_bucket.s3_bucket_id

  lb_access_queries_create   = true
  lb_access_log_bucket_name  = var.cbs_satellite_bucket_name
  waf_access_queries_create  = true
  waf_access_log_bucket_name = var.cbs_satellite_bucket_name

  billing_tag_value = var.billing_tag_value
}

#
# Hold the Athena result data
#
module "athena_bucket" {
  source            = "github.com/cds-snc/terraform-modules//S3?ref=v10.6.2"
  bucket_name       = "gc-articles-${var.env}-athena"
  billing_tag_value = var.billing_tag_value

  lifecycle_rule = [
    {
      id      = "expire-objects"
      enabled = true
      expiration = {
        days = 30
      }
    },
  ]
}
