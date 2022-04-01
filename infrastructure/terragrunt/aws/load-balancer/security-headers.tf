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
    # content_security_policy {
    #   content_security_policy = "base-uri 'self'; connect-src 'self'; default-src 'self'; font-src 'self' https://fonts.gstatic.com https://use.fontawesome.com https://www.canada.ca; frame-src 'self'; img-src 'self' https://canada.ca https://wet-boew.github.io https://www.canada.ca; manifest-src 'self'; media-src 'self'; object-src 'none'; script-src 'self' https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.js https://www.canada.ca/etc/designs/canada/wet-boew/js/wet-boew.min.js; style-src 'self' https://use.fontawesome.com https://www.canada.ca; worker-src 'none';"
    #   override                = true
    # }
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
    #   content_security_policy = "base-uri 'self'; connect-src 'self'; default-src 'self'; font-src 'self' https://fonts.gstatic.com https://use.fontawesome.com https://www.canada.ca; frame-src 'self'; img-src 'self' https://canada.ca https://wet-boew.github.io https://www.canada.ca; manifest-src 'self'; media-src 'self'; object-src 'none'; script-src 'self' 'unsafe-inline' https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.js https://www.canada.ca/etc/designs/canada/wet-boew/js/wet-boew.min.js; style-src 'self' 'unsafe-inline' https://use.fontawesome.com https://www.canada.ca; worker-src 'none';"
    #   override                = true
    # }
  }
}
