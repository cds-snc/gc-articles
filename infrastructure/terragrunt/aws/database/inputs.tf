variable "database_instances_count" {
  type = number
}

variable "database_instance_class" {
  type = string
}

variable "database_name" {
  type      = string
  sensitive = true
}

variable "database_username" {
  type      = string
  sensitive = true
}

variable "database_password" {
  type      = string
  sensitive = true
}

variable "private_subnet_ids" {
  type = list(any)
}

variable "vpc_id" {
  type = string
}
