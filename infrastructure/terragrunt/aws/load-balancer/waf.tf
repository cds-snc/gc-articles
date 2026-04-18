locals {
  # Rules that must be excluded from the AWSManagedRulesCommonRuleSet for WordPress to work
  bot_control_excluded_rules   = ["CategoryHttpLibrary", "SignalNonBrowserUserAgent"]
  common_excluded_rules        = ["GenericRFI_QUERYARGUMENTS", "GenericRFI_BODY", "GenericRFI_URIPATH", "CrossSiteScripting_BODY", "SizeRestrictions_BODY"]
  php_excluded_rules           = ["PHPHighRiskMethodsVariables_BODY"]
  rate_limit_all_requests      = 1000
  rate_limit_mutating_requests = 200
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
    name     = "InvalidHost"
    priority = 1

    action {
      dynamic "block" {
        for_each = var.enable_waf == true ? [""] : []
        content {}
      }

      dynamic "count" {
        for_each = var.enable_waf == false ? [""] : []
        content {}
      }
    }

    statement {
      not_statement {
        statement {
          byte_match_statement {
            field_to_match {
              single_header {
                name = "host"
              }
            }
            text_transformation {
              priority = 1
              type     = "COMPRESS_WHITE_SPACE"
            }
            text_transformation {
              priority = 2
              type     = "LOWERCASE"
            }
            positional_constraint = "EXACTLY"
            search_string         = var.domain_name
          }
        }
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "InvalidHost"
      sampled_requests_enabled   = true
    }
  }

  rule {
    name     = "GeoRestriction"
    priority = 10

    action {
      dynamic "block" {
        for_each = var.enable_waf == true ? [""] : []
        content {}
      }

      dynamic "count" {
        for_each = var.enable_waf == false ? [""] : []
        content {}
      }
    }

    statement {
      not_statement {
        statement {
          or_statement {
            statement {
              geo_match_statement {
                country_codes = ["CA", "US"]
              }
            }
            statement {
              byte_match_statement {
                positional_constraint = "EXACTLY"
                field_to_match {
                  single_header {
                    name = "waf-secret"
                  }
                }
                search_string = var.cloudfront_waf_geo_match_secret
                text_transformation {
                  priority = 1
                  type     = "NONE"
                }
              }
            }
          }
        }
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "GeoRestriction"
      sampled_requests_enabled   = true
    }
  }

  rule {
    name     = "RateLimitAllRequestsIp"
    priority = 20

    action {
      dynamic "block" {
        for_each = var.enable_waf == true ? [""] : []
        content {}
      }

      dynamic "count" {
        for_each = var.enable_waf == false ? [""] : []
        content {}
      }
    }

    statement {
      rate_based_statement {
        limit              = local.rate_limit_all_requests
        aggregate_key_type = "IP"
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "RateLimitAllRequestsIp"
      sampled_requests_enabled   = true
    }
  }

  rule {
    name     = "RateLimitAllRequestsJA4"
    priority = 30

    action {
      dynamic "block" {
        for_each = var.enable_waf == true ? [""] : []
        content {}
      }

      dynamic "count" {
        for_each = var.enable_waf == false ? [""] : []
        content {}
      }
    }

    statement {
      rate_based_statement {
        limit              = local.rate_limit_all_requests
        aggregate_key_type = "CUSTOM_KEYS"

        custom_key {
          ja4_fingerprint {
            fallback_behavior = "MATCH"
          }
        }
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "RateLimitAllRequestsJA4"
      sampled_requests_enabled   = true
    }
  }

  rule {
    name     = "RateLimitMutatingRequestsIp"
    priority = 40

    action {
      dynamic "block" {
        for_each = var.enable_waf == true ? [""] : []
        content {}
      }

      dynamic "count" {
        for_each = var.enable_waf == false ? [""] : []
        content {}
      }
    }

    statement {
      rate_based_statement {
        limit              = local.rate_limit_mutating_requests
        aggregate_key_type = "IP"
        scope_down_statement {
          regex_match_statement {
            field_to_match {
              method {}
            }
            regex_string = "^(delete|patch|post|put)$"
            text_transformation {
              priority = 1
              type     = "LOWERCASE"
            }
          }
        }
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "RateLimitMutatingRequestsIp"
      sampled_requests_enabled   = true
    }
  }

  rule {
    name     = "RateLimitMutatingRequestsJA4"
    priority = 50

    action {
      dynamic "block" {
        for_each = var.enable_waf == true ? [""] : []
        content {}
      }

      dynamic "count" {
        for_each = var.enable_waf == false ? [""] : []
        content {}
      }
    }

    statement {
      rate_based_statement {
        limit              = local.rate_limit_mutating_requests
        aggregate_key_type = "CUSTOM_KEYS"

        custom_key {
          ja4_fingerprint {
            fallback_behavior = "MATCH"
          }
        }

        scope_down_statement {
          regex_match_statement {
            field_to_match {
              method {}
            }
            regex_string = "^(delete|patch|post|put)$"
            text_transformation {
              priority = 1
              type     = "LOWERCASE"
            }
          }
        }
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "RateLimitMutatingRequestsJA4"
      sampled_requests_enabled   = true
    }
  }

  rule {
    name     = "AWSManagedRulesAmazonIpReputationList"
    priority = 60

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
    name     = "AWSManagedRulesCommonRuleSet"
    priority = 70

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
    priority = 80

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
    priority = 90

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
    priority = 110

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
    priority = 120

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
    priority = 130

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
    priority = 140

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
    priority = 150

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
    name     = "Custom_SizeRestrictions_BODY"
    priority = 160
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
    priority = 170
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
    priority = 180
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
    name     = "AWSManagedRulesAntiDDoSRuleSet"
    priority = 190
    override_action {
      none {}
    }
    statement {
      managed_rule_group_statement {
        name        = "AWSManagedRulesAntiDDoSRuleSet"
        vendor_name = "AWS"

        managed_rule_group_configs {
          aws_managed_rules_anti_ddos_rule_set {
            client_side_action_config {
              challenge {
                sensitivity     = "HIGH"
                usage_of_action = "ENABLED"
                exempt_uri_regular_expression {
                  regex_string = ".(acc|avi|css|gif|jpe?g|js|pdf|png|tiff?|ttf|webm|webp|woff2?)$"
                }
              }
            }
          }
        }
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "AWSManagedRulesAntiDDoSRuleSet"
      sampled_requests_enabled   = true
    }
  }

  rule {
    name     = "BlockComments"
    priority = 200

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
    name     = "BotControl"
    priority = 210

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
        name        = "AWSManagedRulesBotControlRuleSet"
        vendor_name = "AWS"

        scope_down_statement {
          regex_match_statement {
            field_to_match {
              method {}
            }
            regex_string = "^.*/(sign-in-se-connecter|wp-admin|wp-json|wp-content).*$"
            text_transformation {
              priority = 1
              type     = "LOWERCASE"
            }
          }
        }

        dynamic "rule_action_override" {
          for_each = local.bot_control_excluded_rules
          content {
            name = rule_action_override.value
            action_to_use {
              count {}
            }
          }
        }

        managed_rule_group_configs {
          aws_managed_rules_bot_control_rule_set {
            inspection_level = "COMMON"
          }
        }
      }
    }

    visibility_config {
      cloudwatch_metrics_enabled = true
      metric_name                = "BotControl"
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
# WAF logging
#
resource "aws_wafv2_web_acl_logging_configuration" "firehose_waf_logs_cloudfront" {
  provider = aws.us-east-1

  log_destination_configs = [aws_kinesis_firehose_delivery_stream.firehose_waf_logs_us_east.arn]
  resource_arn            = aws_wafv2_web_acl.wordpress_waf.arn
}
