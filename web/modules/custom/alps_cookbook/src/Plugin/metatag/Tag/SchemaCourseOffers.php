<?php

namespace Drupal\alps_cookbook\Plugin\metatag\Tag;

/**
 * Provides a plugin for the 'offers' meta tag.
 *
 * - 'id' should be a globally unique id.
 * - 'name' should match the Schema.org element name.
 * - 'group' should match the id of the group that defines the Schema.org type.
 *
 * @MetatagTag(
 *   id = "schema_course_offers",
 *   label = @Translation("offers"),
 *   description = @Translation("RECOMMENDED BY GOOGLE. Offers associated with the course."),
 *   name = "offers",
 *   group = "schema_course",
 *   weight = -50,
 *   type = "string",
 *   secure = FALSE,
 *   multiple = TRUE
 * )
 */
class SchemaCourseOffers extends SchemaCourseOffersBase {

}
