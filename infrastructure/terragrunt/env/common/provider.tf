
terraform {
  required_version = "1.11.2"
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 5.0"
    }
  }
}

provider "aws" {
  region              = var.region
  allowed_account_ids = [var.account_id]
}

provider "aws" {
  alias  = "us-east-1"
  region = "us-east-1"
}
