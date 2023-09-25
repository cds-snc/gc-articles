locals {
  ecr_push_role = "gc-articles-ecr-push"
}

module "ecr_push_role" {
  source            = "github.com/cds-snc/terraform-modules//gh_oidc_role?ref=v7.0.2"
  billing_tag_value = var.billing_tag_value
  roles = [
    {
      name      = local.ecr_push_role
      repo_name = "gc-articles"
      claim     = "ref:refs/tags/v*"
    }
  ]
}

data "aws_iam_policy" "ecr_read_write" {
  name = "AmazonEC2ContainerRegistryPowerUser"
}

resource "aws_iam_role_policy_attachment" "ecr_push_role" {
  role       = local.ecr_push_role
  policy_arn = data.aws_iam_policy.ecr_read_write.arn
  depends_on = [module.ecr_push_role]
}
