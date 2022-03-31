resource "aws_cloudfront_response_headers_policy" "security_headers_policy" {
  name = "gc-articles-security-headers"
  security_headers_config {
    content_type_options {
      override = false
    }
    frame_options {
      override = false
    }
    xss_protection {
      override = false
    }
    strict_transport_security {
      override = false
    }
    content_security_policy {
      override = false
    }
  }
}
