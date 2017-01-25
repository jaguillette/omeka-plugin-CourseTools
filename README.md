# Course Tools

This Omeka plugin adds two new user roles to Omeka: reviewers and students.

Reviewers are simply researchers with expanded read access. While a researcher
role may be able to view Items and Collections that are not public, they may
run into trouble with Exhibits, Neatline Exhibits, and other types of content
added by plugins. The reviewer role is intended to expand that access. In a
classroom setting, this role allows for other instructors or students to view
content created on the site that may not be public, as in the case of student
work that the student wishes to keep from the wider web, but share with their
advisor.

Students have customizable permissions. In the configuration for this plugin,
you can set the permission levels for students for individual content types,
such as Items, Collections, or Exhibits. These permissions update as soon as
you set them, so you can change what students have access to at different times
in the semester, such as around grading, due dates, or peer review. These
custom permissions also separate out delete permissions, so you can prevent
students from accidentally deleting their work, if you choose. Content can
still be deleted by an Administrator or Super User, but it can keep a student
from making a costly mistake.

## Installation

You can install this plugin by cloning this repository with git, or by adding this repository's zip folder to your site's plugins directory.

### Git Installation

Navigate to your site's plugins directory, then run

    git clone https://github.com/jaguillette/omeka-plugin-CourseTools.git CourseTools

Make sure that it clones into a directory called "CourseTools" or the plugin won't function.

### Zip File Installation

1. Click the green "Clone or Download" button above the file list on this page.
2. Click "Download Zip"
3. Choose a save location
4. Extract the zip file, and rename the resulting folder "CourseTools"
5. Upload that folder to your site's plugins folder.
