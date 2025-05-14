locals {
  # Rules that must be excluded from the AWSManagedRulesCommonRuleSet for WordPress to work
  common_excluded_rules = ["GenericRFI_QUERYARGUMENTS", "GenericRFI_BODY", "GenericRFI_URIPATH", "CrossSiteScripting_BODY", "SizeRestrictions_BODY"]
  php_excluded_rules    = ["PHPHighRiskMethodsVariables_BODY"]
}

#
# CloudFront access control
#
resource "aws_wafv2_web_acl" "wordpress_waf" {
  provider = aws.us-east-1

  name        = "wordpress_waf"
  description = "WAF for Wordpress protection"
  scope       = "CLOUDFRONT"

  default_action {
    allow {}
  }

  rule {
    name     = "AWSManagedRulesCommonRuleSet"
    priority = 1

    override_action {
      dynamic "none" {
        for_each = var.enable_waf == true ? [""] : []
        content {
        }
      }

      dynamic "count" {
        for_each = var.enable_waf == false ? [""] : []
        content {
        }
      }
    }

    statement {
      managed_rule_group_statement {
        name        = "AWSManagedRulesCommonRuleSet"
        vendor_name = "AWS"

        dynamic "rule_action_override" {
          for_each = local.common_excluded_rules
          content {
            name = rule_action_override.value
            action_to_use {
              count {}
            }
          }
        }
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "AWSManagedRulesCommonRuleSet"
      sampled_requests_enabled   = true
    }
  }

  rule {
    name     = "AWSManagedRulesKnownBadInputsRuleSet"
    priority = 2

    override_action {
      dynamic "none" {
        for_each = var.enable_waf == true ? [""] : []
        content {
        }
      }

      dynamic "count" {
        for_each = var.enable_waf == false ? [""] : []
        content {
        }
      }
    }

    statement {
      managed_rule_group_statement {
        name        = "AWSManagedRulesKnownBadInputsRuleSet"
        vendor_name = "AWS"
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "AWSManagedRulesKnownBadInputsRuleSet"
      sampled_requests_enabled   = true
    }
  }

  rule {
    name     = "AWSManagedRulesLinuxRuleSet"
    priority = 3

    override_action {
      dynamic "none" {
        for_each = var.enable_waf == true ? [""] : []
        content {
        }
      }

      dynamic "count" {
        for_each = var.enable_waf == false ? [""] : []
        content {
        }
      }
    }

    statement {
      managed_rule_group_statement {
        name        = "AWSManagedRulesLinuxRuleSet"
        vendor_name = "AWS"
        rule_action_override {
          name = "LFI_QUERYSTRING"
          action_to_use {
            count {}
          }
        }
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "AWSManagedRulesLinuxRuleSet"
      sampled_requests_enabled   = true
    }
  }

  rule {
    name     = "Custom_LFI_QueryString"
    priority = 4

    action {
      dynamic "block" {
        for_each = var.enable_waf == true ? [""] : []
        content {
        }
      }

      dynamic "count" {
        for_each = var.enable_waf == false ? [""] : []
        content {
        }
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "Custom_LFI_QueryString"
      sampled_requests_enabled   = true
    }

    statement {
      and_statement {
        statement {
          label_match_statement {
            scope = "LABEL"
            key   = "awswaf:managed:aws:linux-os:LFI_QueryString"
          }
        }
        statement {
          not_statement {
            statement {
              byte_match_statement {
                field_to_match {
                  uri_path {}
                }
                positional_constraint = "CONTAINS"
                search_string         = "/wp-admin"
                text_transformation {
                  type     = "LOWERCASE"
                  priority = 0
                }
              }
            }
          }
        }
      }
    }
  }

  rule {
    name     = "AWSManagedRulesSQLiRuleSet"
    priority = 5

    override_action {
      dynamic "none" {
        for_each = var.enable_waf == true ? [""] : []
        content {
        }
      }

      dynamic "count" {
        for_each = var.enable_waf == false ? [""] : []
        content {
        }
      }
    }

    statement {
      managed_rule_group_statement {
        name        = "AWSManagedRulesSQLiRuleSet"
        vendor_name = "AWS"
        rule_action_override {
          name = "SQLi_BODY"
          action_to_use {
            count {}
          }
        }
        rule_action_override {
          name = "SQLiExtendedPatterns_Body"
          action_to_use {
            count {}
          }
        }
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "AWSManagedRulesSQLiRuleSet"
      sampled_requests_enabled   = true
    }
  }

  rule {
    name     = "Custom_SQLi_BODY"
    priority = 6

    action {
      dynamic "block" {
        for_each = var.enable_waf == true ? [""] : []
        content {
        }
      }

      dynamic "count" {
        for_each = var.enable_waf == false ? [""] : []
        content {
        }
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "Custom_SQLi_BODY"
      sampled_requests_enabled   = true
    }

    statement {
      and_statement {
        statement {
          label_match_statement {
            scope = "LABEL"
            key   = "awswaf:managed:aws:sql-database:SQLi_Body"
          }
        }
        statement {
          not_statement {
            statement {
              or_statement {
                statement {
                  byte_match_statement {
                    field_to_match {
                      uri_path {}
                    }
                    positional_constraint = "CONTAINS"
                    search_string         = "sign-in-se-connecter"
                    text_transformation {
                      type     = "LOWERCASE"
                      priority = 0
                    }
                  }
                }
                statement {
                  byte_match_statement {
                    field_to_match {
                      uri_path {}
                    }
                    positional_constraint = "CONTAINS"
                    search_string         = "async-upload.php"
                    text_transformation {
                      type     = "LOWERCASE"
                      priority = 0
                    }
                  }
                }
                statement {
                  byte_match_statement {
                    field_to_match {
                      uri_path {}
                    }
                    positional_constraint = "CONTAINS"
                    search_string         = "media-new.php"
                    text_transformation {
                      type     = "LOWERCASE"
                      priority = 0
                    }
                  }
                }
                statement {
                  byte_match_statement {
                    field_to_match {
                      uri_path {}
                    }
                    positional_constraint = "CONTAINS"
                    search_string         = "wp-json/wp/v2/media"
                    text_transformation {
                      type     = "LOWERCASE"
                      priority = 0
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }

  rule {
    name     = "Custom_SQLiExtendedPatterns_BODY"
    priority = 7

    action {
      dynamic "block" {
        for_each = var.enable_waf == true ? [""] : []
        content {
        }
      }

      dynamic "count" {
        for_each = var.enable_waf == false ? [""] : []
        content {
        }
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "Custom_SQLiExtendedPatterns_BODY"
      sampled_requests_enabled   = true
    }

    statement {
      and_statement {
        statement {
          label_match_statement {
            scope = "LABEL"
            key   = "awswaf:managed:aws:sql-database:SQLiExtendedPatterns_Body"
          }
        }
        statement {
          not_statement {
            statement {
              byte_match_statement {
                field_to_match {
                  uri_path {}
                }
                positional_constraint = "CONTAINS"
                search_string         = "wp-json"
                text_transformation {
                  type     = "LOWERCASE"
                  priority = 0
                }
              }
            }
          }
        }
      }
    }
  }

  rule {
    name     = "AWSManagedRulesPHPRuleSet"
    priority = 8

    override_action {
      dynamic "none" {
        for_each = var.enable_waf == true ? [""] : []
        content {
        }
      }

      dynamic "count" {
        for_each = var.enable_waf == false ? [""] : []
        content {
        }
      }
    }

    statement {
      managed_rule_group_statement {
        name        = "AWSManagedRulesPHPRuleSet"
        vendor_name = "AWS"

        dynamic "rule_action_override" {
          for_each = local.php_excluded_rules
          content {
            name = rule_action_override.value
            action_to_use {
              count {}
            }
          }
        }
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "AWSManagedRulesPHPRuleSet"
      sampled_requests_enabled   = true
    }
  }

  rule {
    name     = "AWSManagedRulesWordPressRuleSet"
    priority = 9

    override_action {
      dynamic "none" {
        for_each = var.enable_waf == true ? [""] : []
        content {
        }
      }

      dynamic "count" {
        for_each = var.enable_waf == false ? [""] : []
        content {
        }
      }
    }

    statement {
      managed_rule_group_statement {
        name        = "AWSManagedRulesWordPressRuleSet"
        vendor_name = "AWS"
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "AWSManagedRulesWordPressRuleSet"
      sampled_requests_enabled   = true
    }
  }

  rule {
    name     = "AWSManagedRulesAmazonIpReputationList"
    priority = 10

    override_action {
      dynamic "none" {
        for_each = var.enable_waf == true ? [""] : []
        content {
        }
      }

      dynamic "count" {
        for_each = var.enable_waf == false ? [""] : []
        content {
        }
      }
    }

    statement {
      managed_rule_group_statement {
        name        = "AWSManagedRulesAmazonIpReputationList"
        vendor_name = "AWS"
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "AWSManagedRulesAmazonIpReputationList"
      sampled_requests_enabled   = true
    }
  }

  rule {
    name     = "Custom_SizeRestrictions_BODY"
    priority = 11
    action {
      dynamic "block" {
        for_each = var.enable_waf == true ? [""] : []
        content {
        }
      }

      dynamic "count" {
        for_each = var.enable_waf == false ? [""] : []
        content {
        }
      }
    }

    visibility_config {
      sampled_requests_enabled   = true
      cloudwatch_metrics_enabled = true
      metric_name                = "Custom_SizeRestrictions_BODY"
    }

    statement {
      and_statement {
        statement {
          size_constraint_statement {
            field_to_match {
              body {
                oversize_handling = "CONTINUE"
              }
            }
            comparison_operator = "GT"
            size                = "16384"
            text_transformation {
              type     = "NONE"
              priority = 0
            }
          }
        }
        statement {
          not_statement {
            statement {
              byte_match_statement {
                field_to_match {
                  uri_path {}
                }
                positional_constraint = "CONTAINS"
                search_string         = "/wp-admin"
                text_transformation {
                  type     = "NONE"
                  priority = 0
                }
              }
            }
          }
        }
        statement {
          not_statement {
            statement {
              byte_match_statement {
                field_to_match {
                  uri_path {}
                }
                positional_constraint = "CONTAINS"
                search_string         = "/wp-json"
                text_transformation {
                  type     = "NONE"
                  priority = 0
                }
              }
            }
          }
        }
      }
    }
  }

  rule {
    name     = "Custom_CrossSiteScripting_BODY"
    priority = 12
    action {
      dynamic "block" {
        for_each = var.enable_waf == true ? [""] : []
        content {
        }
      }

      dynamic "count" {
        for_each = var.enable_waf == false ? [""] : []
        content {
        }
      }
    }

    visibility_config {
      sampled_requests_enabled   = true
      cloudwatch_metrics_enabled = true
      metric_name                = "Custom_CrossSiteScripting_BODY"
    }

    statement {
      and_statement {
        statement {
          xss_match_statement {
            field_to_match {
              body {
                oversize_handling = "CONTINUE"
              }
            }
            text_transformation {
              type     = "NONE"
              priority = 0
            }
          }
        }
        statement {
          not_statement {
            statement {
              byte_match_statement {
                field_to_match {
                  uri_path {}
                }
                positional_constraint = "CONTAINS"
                search_string         = "/wp-admin"
                text_transformation {
                  type     = "NONE"
                  priority = 0
                }
              }
            }
          }
        }
        statement {
          not_statement {
            statement {
              byte_match_statement {
                field_to_match {
                  uri_path {}
                }
                positional_constraint = "CONTAINS"
                search_string         = "/wp-json"
                text_transformation {
                  type     = "NONE"
                  priority = 0
                }
              }
            }
          }
        }
      }
    }
  }

  rule {
    name     = "Custom_PHPHighRiskMethodsVariables_BODY"
    priority = 90
    action {
      dynamic "block" {
        for_each = var.enable_waf == true ? [""] : []
        content {
        }
      }

      dynamic "count" {
        for_each = var.enable_waf == false ? [""] : []
        content {
        }
      }
    }

    visibility_config {
      sampled_requests_enabled   = true
      cloudwatch_metrics_enabled = true
      metric_name                = "Custom_PHPHighRiskMethodsVariables_BODY"
    }

    statement {
      and_statement {
        statement {
          label_match_statement {
            scope = "LABEL"
            key   = "awswaf:managed:aws:sql-database:PHPHighRiskMethodsVariables_Body"
          }
        }
        statement {
          not_statement {
            statement {
              and_statement {
                statement {
                  byte_match_statement {
                    positional_constraint = "EXACTLY"
                    search_string         = "post"
                    field_to_match {
                      method {}
                    }
                    text_transformation {
                      priority = 0
                      type     = "LOWERCASE"
                    }
                  }
                }
                statement {
                  byte_match_statement {
                    field_to_match {
                      uri_path {}
                    }
                    positional_constraint = "CONTAINS"
                    search_string         = "/wp-json/"
                    text_transformation {
                      type     = "LOWERCASE"
                      priority = 0
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }

  rule {
    name     = "BlockComments"
    priority = 100

    action {
      dynamic "block" {
        for_each = var.enable_waf == true ? [""] : []
        content {
        }
      }

      dynamic "count" {
        for_each = var.enable_waf == false ? [""] : []
        content {
        }
      }
    }

    statement {
      and_statement {
        statement {
          byte_match_statement {
            positional_constraint = "CONTAINS"
            search_string         = "wp-comments-post.php"
            field_to_match {
              uri_path {}
            }
            text_transformation {
              priority = 0
              type     = "LOWERCASE"
            }
          }
        }

        statement {
          byte_match_statement {
            positional_constraint = "EXACTLY"
            search_string         = "post"
            field_to_match {
              method {}
            }
            text_transformation {
              priority = 0
              type     = "LOWERCASE"
            }
          }
        }
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "BlockComments"
      sampled_requests_enabled   = true
    }
  }

  rule {
    name     = "WordpressRateLimit"
    priority = 110

    action {
      dynamic "block" {
        for_each = var.enable_waf == true ? [""] : []
        content {
          custom_response {
            response_code = 429
            response_header {
              name  = "waf-block"
              value = "RateLimit"
            }
          }
        }
      }

      dynamic "count" {
        for_each = var.enable_waf == false ? [""] : []
        content {
        }
      }
    }

    statement {
      rate_based_statement {
        limit              = 2000
        aggregate_key_type = "IP"
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "WordpressRateLimit"
      sampled_requests_enabled   = true
    }
  }

  rule {
    name     = "BlockedIPv4"
    priority = 120

    action {
      count {}
    }

    statement {
      ip_set_reference_statement {
        arn = module.waf_ip_blocklist.ipv4_blocklist_arn
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "BlockedIPv4"
      sampled_requests_enabled   = true
    }
  }

  visibility_config {
    cloudwatch_metrics_enabled = true
    metric_name                = "wordpress"
    sampled_requests_enabled   = false
  }

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
  }
}

#
# ALB access control: block if custom header not specified
#
resource "aws_wafv2_web_acl" "wordpress_waf_alb" {
  name  = "wordpress_waf_alb"
  scope = "REGIONAL"

  default_action {
    block {}
  }

  rule {
    name     = "CloudFrontCustomHeader"
    priority = 201

    action {
      allow {}
    }

    statement {
      byte_match_statement {
        positional_constraint = "EXACTLY"
        field_to_match {
          single_header {
            name = var.cloudfront_custom_header_name
          }
        }
        search_string = var.cloudfront_custom_header_value
        text_transformation {
          priority = 1
          type     = "NONE"
        }
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "CloudFrontCustomHeader"
      sampled_requests_enabled   = false
    }
  }

  visibility_config {
    cloudwatch_metrics_enabled = true
    metric_name                = "CloudFrontCustomHeader"
    sampled_requests_enabled   = false
  }

  tags = {
    (var.billing_tag_key) = var.billing_tag_value
  }
}

#
# WAF association
#
resource "aws_wafv2_web_acl_association" "wordpress_waf_alb" {
  resource_arn = aws_lb.wordpress.arn
  web_acl_arn  = aws_wafv2_web_acl.wordpress_waf_alb.arn
}

#
# WAF logging
#
resource "aws_wafv2_web_acl_logging_configuration" "firehose_waf_logs_cloudfront" {
  provider = aws.us-east-1

  log_destination_configs = [aws_kinesis_firehose_delivery_stream.firehose_waf_logs_us_east.arn]
  resource_arn            = aws_wafv2_web_acl.wordpress_waf.arn
}

resource "aws_wafv2_web_acl_logging_configuration" "firehose_waf_logs_alb" {
  log_destination_configs = [aws_kinesis_firehose_delivery_stream.firehose_waf_logs.arn]
  resource_arn            = aws_wafv2_web_acl.wordpress_waf_alb.arn
}

#
# IPv4 blocklist that is automatically managed by a Lambda function.  Any IP address in the WAF logs
# that crosses a block threshold will be added to the blocklist.
#
module "waf_ip_blocklist" {
  source = "github.com/cds-snc/terraform-modules//waf_ip_blocklist?ref=v10.4.6"

  # IP blocklist must be in us-east-1 as the CloudFront WAF
  # requires it to work with the IP set
  providers = {
    aws = aws.us-east-1
  }

  service_name                = "gc-articles"
  athena_database_name        = "access_logs"
  athena_query_results_bucket = module.athena_bucket.s3_bucket_id
  athena_query_source_bucket  = var.cbs_satellite_bucket_name
  athena_lb_table_name        = "lb_logs"
  athena_waf_table_name       = "waf_logs"
  athena_workgroup_name       = "logs"
  athena_region               = var.region

  waf_scope                        = "CLOUDFRONT"
  waf_ip_blocklist_update_schedule = "rate(1 hour)"

  billing_tag_value = var.billing_tag_value
}
