resource "aws_cloudfront_response_headers_policy" "security_headers_policy_frontend" {
  name = "gc-articles-security-headers-frontend"
  security_headers_config {
    content_type_options {
      override = true
    }
    frame_options {
      frame_option = "SAMEORIGIN"
      override     = true
    }
    xss_protection {
      mode_block = true
      protection = true
      override   = true
    }
    strict_transport_security {
      access_control_max_age_sec = "31536000"
      include_subdomains         = true
      preload                    = true
      override                   = true
    }
    content_security_policy {
      content_security_policy = "base-uri 'self'; connect-src 'self'; default-src 'self'; font-src 'self' https://fonts.gstatic.com https://use.fontawesome.com https://www.canada.ca; frame-src 'self'; img-src 'self' https://canada.ca https://wet-boew.github.io https://www.canada.ca; manifest-src 'self'; media-src 'self'; object-src 'none'; script-src 'self' 'sha256-DdN0UNltr41cvBTgBr0owkshPbwM95WknOV9rvTA7pg=' 'sha256-MF5ZCDqcQxsjnFVq0T7A8bpEWUJiuO9Qx1MqSYvCwds=' https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.js https://www.canada.ca/etc/designs/canada/wet-boew/js/wet-boew.min.js https://www.canada.ca/etc/designs/canada/wet-boew/js/theme.min.js https://www.canada.ca/etc/designs/canada/wet-boew/js/i18n/en.min.js; style-src 'self' 'sha256-JyCJ6ZZTV5uYG6rFk9V5g2xnONEgHcTb0bykLClbiZs=' 'sha256-ajz1GSNU9xYVrFEDSz6Xwg7amWQ/yvW75tQa1ZvRIWc=' 'sha256-ajz1GSNU9xYVrFEDSz6Xwg7amWQ/yvW75tQa1ZvRIWc=' 'sha256-ajz1GSNU9xYVrFEDSz6Xwg7amWQ/yvW75tQa1ZvRIWc=' 'sha256-BbI6awNMygKJzIJX7tpQhX3Y/llSMp8j1X3GNmEMq1w=' 'sha256-VqrnF4B4J9Y4bPMr7eFvVwQZZUT48w5WJm29LDfS7Dk=' 'sha256-LpfmXS+4ZtL2uPRZgkoR29Ghbxcfime/CsD/4w5VujE=' 'sha256-+6P3k/sFK1YWQJ1ATWx5iGDZgIUy3TjBQy89fF6M/OQ='  https://use.fontawesome.com https://www.canada.ca; worker-src 'none';"
      override                = true
    }
  }
}

resource "aws_cloudfront_response_headers_policy" "security_headers_policy_admin" {
  name = "gc-articles-security-headers-admin"
  security_headers_config {
    content_type_options {
      override = true
    }
    frame_options {
      frame_option = "SAMEORIGIN"
      override     = true
    }
    xss_protection {
      mode_block = true
      protection = true
      override   = true
    }
    strict_transport_security {
      access_control_max_age_sec = "31536000"
      include_subdomains         = true
      preload                    = true
      override                   = true
    }
    # content_security_policy {
    #   content_security_policy = "base-uri 'self'; connect-src 'self'; default-src 'self'; font-src 'self' https://fonts.gstatic.com https://use.fontawesome.com https://www.canada.ca; frame-src 'self'; img-src 'self' https://canada.ca https://wet-boew.github.io https://www.canada.ca; manifest-src 'self'; media-src 'self'; object-src 'none'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.js https://www.canada.ca/etc/designs/canada/wet-boew/js/wet-boew.min.js; style-src 'self' 'unsafe-inline' https://use.fontawesome.com https://www.canada.ca https://fonts.googleapis.com; worker-src 'none';"
    #   override                = true
    # }
  }
}
