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
      content_security_policy = "base-uri 'self'; connect-src 'self'; default-src 'self'; font-src 'self' data: https://fonts.gstatic.com https://use.fontawesome.com https://www.canada.ca; frame-src 'self'; img-src 'self' data: https://canada.ca https://wet-boew.github.io https://www.canada.ca https://secure.gravatar.com; manifest-src 'self'; media-src 'self'; object-src 'none'; script-src 'self' 'sha256-DdN0UNltr41cvBTgBr0owkshPbwM95WknOV9rvTA7pg=' 'sha256-MF5ZCDqcQxsjnFVq0T7A8bpEWUJiuO9Qx1MqSYvCwds=' 'sha256-8//zSBdstORCAlBMo1/Cig3gKc7QlPCh9QfWbRu0OjU=' https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.js https://www.canada.ca/etc/designs/canada/wet-boew/js/wet-boew.min.js https://www.canada.ca/etc/designs/canada/wet-boew/js/theme.min.js https://www.canada.ca/etc/designs/canada/wet-boew/js/i18n/en.min.js; style-src 'self' 'unsafe-inline' https://use.fontawesome.com https://www.canada.ca; worker-src 'none';"
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

resource "aws_cloudfront_response_headers_policy" "security_headers_policy_api" {
  name = "gc-articles-security-headers-api"
  security_headers_config {
    strict_transport_security {
      access_control_max_age_sec = "31536000"
      include_subdomains         = true
      preload                    = true
      override                   = true
    }
  }
}
