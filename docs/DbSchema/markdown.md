#VLearn
Generated using [DbSchema](https://dbschema.com)





<a name='layout1'>### Default Layout
![img](file:/home/sownbanana/HUST-graduate-project/docs/DbSchema/markdown/DefaultLayout.svg)






### Table answers 
| Idx | Field Name | Data Type |
|---|---|---|
| *🔑 | <a name='vlearn.answers_id'>id</a>| int UNSIGNED AUTO_INCREMENT |
| *⬈ | <a name='vlearn.answers_question_id'>question&#95;id</a>| int UNSIGNED  |
|  | <a name='vlearn.answers_content'>content</a>| varchar&#40;250&#41;  |
|  | <a name='vlearn.answers_is_true'>is&#95;true</a>| boolean  |
| Indexes 
| 🔑 | pk&#95;answer&#95;id || ON id|| Foreign Keys |  | fk_answer_learn_unit | ( question&#95;id ) ref [vlearn&#46;questions](#questions) (id) 
|| Options |
| 3 |


### Table asset_lesson 
| Idx | Field Name | Data Type |
|---|---|---|
| ⬈ | <a name='vlearn.asset_lesson_asset_id'>asset&#95;id</a>| int UNSIGNED  |
| ⬈ | <a name='vlearn.asset_lesson_lesson_id'>lesson&#95;id</a>| int UNSIGNED  |
| Foreign Keys |  | fk_asset_lesson_assets | ( asset&#95;id ) ref [vlearn&#46;assets](#assets) (id) 
||  | fk_asset_lesson_lessons | ( lesson&#95;id ) ref [vlearn&#46;lessons](#lessons) (id) 
|| Options |
| 3 |


### Table asset_message 
| Idx | Field Name | Data Type |
|---|---|---|
| ⬈ | <a name='vlearn.asset_message_asset_id'>asset&#95;id</a>| int UNSIGNED  |
| ⬈ | <a name='vlearn.asset_message_message_id'>message&#95;id</a>| int UNSIGNED  |
| Foreign Keys |  | fk_asset_message_assets | ( asset&#95;id ) ref [vlearn&#46;assets](#assets) (id) 
||  | fk_asset_message_messages | ( message&#95;id ) ref [vlearn&#46;messages](#messages) (id) 
|| Options |
| 3 |


### Table assets 
| Idx | Field Name | Data Type |
|---|---|---|
| *🔑 ⬋ | <a name='vlearn.assets_id'>id</a>| int UNSIGNED AUTO_INCREMENT |
| ⬈ | <a name='vlearn.assets_owner_id'>owner&#95;id</a>| int UNSIGNED  |
|  | <a name='vlearn.assets_name'>name</a>| varchar&#40;100&#41;  |
|  | <a name='vlearn.assets_url'>url</a>| varchar&#40;100&#41;  |
|  | <a name='vlearn.assets_update_at'>update&#95;at</a>| date  |
|  | <a name='vlearn.assets_is_public'>is&#95;public</a>| boolean  |
| Indexes 
| 🔑 | pk&#95;assets&#95;id || ON id|| Foreign Keys |  | fk_assets_users | ( owner&#95;id ) ref [vlearn&#46;users](#users) (id) 
|| Options |
| 3 |


### Table course_student 
| Idx | Field Name | Data Type |
|---|---|---|
| *🔑 ⬈ | <a name='vlearn.course_student_course_id'>course&#95;id</a>| int UNSIGNED  |
| *🔑 ⬈ | <a name='vlearn.course_student_student_id'>student&#95;id</a>| int UNSIGNED  |
| ⬈ | <a name='vlearn.course_student_section_checkpoint'>section&#95;checkpoint</a>| int UNSIGNED  |
|  | <a name='vlearn.course_student_rate'>rate</a>| int  |
| Indexes 
| 🔑 | pk&#95;course&#95;student || ON student&#95;id&#44; course&#95;id|| Foreign Keys |  | fk_course_student_courses | ( course&#95;id ) ref [vlearn&#46;courses](#courses) (id) 
||  | fk_course_student_users | ( student&#95;id ) ref [vlearn&#46;users](#users) (id) 
||  | fk_course_student_sections | ( section&#95;checkpoint ) ref [vlearn&#46;sections](#sections) (id) 
||  | fk_course_student_section_student | ( section&#95;checkpoint ) ref [vlearn&#46;section&#95;student](#section&#95;student) (section&#95;id) 
|| Options |
| 3 |


### Table course_topic 
| Idx | Field Name | Data Type |
|---|---|---|
| *🔑 ⬈ | <a name='vlearn.course_topic_course_id'>course&#95;id</a>| int UNSIGNED  |
| *🔑 ⬈ | <a name='vlearn.course_topic_topic_id'>topic&#95;id</a>| int UNSIGNED  |
| Indexes 
| 🔑 | pk&#95;course&#95;topic || ON course&#95;id&#44; topic&#95;id|| Foreign Keys |  | fk_course_topic_courses | ( course&#95;id ) ref [vlearn&#46;courses](#courses) (id) 
||  | fk_course_topic_topics | ( topic&#95;id ) ref [vlearn&#46;topics](#topics) (id) 
|| Options |
| 3 |


### Table courses 
| Idx | Field Name | Data Type |
|---|---|---|
| *🔑 ⬋ | <a name='vlearn.courses_id'>id</a>| int UNSIGNED  |
| ⬈ | <a name='vlearn.courses_instructor_id'>instructor&#95;id</a>| int UNSIGNED  |
|  | <a name='vlearn.courses_title'>title</a>| varchar&#40;125&#41;  |
|  | <a name='vlearn.courses_introduce'>introduce</a>| varchar&#40;125&#41;  |
|  | <a name='vlearn.courses_price'>price</a>| int  |
| Indexes 
| 🔑 | pk&#95;courses&#95;id || ON id|| Foreign Keys |  | fk_courses_instructor | ( instructor&#95;id ) ref [vlearn&#46;users](#users) (id) 
|| Options |
| 3 |


### Table instructors 
| Idx | Field Name | Data Type |
|---|---|---|
| *🔑 ⬈ | <a name='vlearn.instructors_user_id'>user&#95;id</a>| int UNSIGNED  |
|  | <a name='vlearn.instructors_introduce'>introduce</a>| varchar&#40;1000&#41;  |
|  | <a name='vlearn.instructors_receive_mail'>receive&#95;mail</a>| varchar&#40;250&#41;  |
|  | <a name='vlearn.instructors_receive_bought_notify'>receive&#95;bought&#95;notify</a>| boolean  |
|  | <a name='vlearn.instructors_receive_report'>receive&#95;report</a>| boolean  |
| Indexes 
| 🔑 | pk&#95;instructors&#95;user&#95;id || ON user&#95;id|| Foreign Keys |  | fk_instructors_users | ( user&#95;id ) ref [vlearn&#46;users](#users) (id) 
|| Options |
| 3 |


### Table lessons 
| Idx | Field Name | Data Type |
|---|---|---|
| *🔑 ⬋ | <a name='vlearn.lessons_id'>id</a>| int UNSIGNED AUTO_INCREMENT |
| *⬈ | <a name='vlearn.lessons_section_id'>section&#95;id</a>| int UNSIGNED  |
|  | <a name='vlearn.lessons_name'>name</a>| varchar&#40;100&#41;  |
|  | <a name='vlearn.lessons_estimate_time'>estimate&#95;time</a>| bigint  |
|  | <a name='vlearn.lessons_video_url'>video&#95;url</a>| varchar&#40;100&#41;  |
|  | <a name='vlearn.lessons_content'>content</a>| varchar&#40;1000&#41;  |
| Indexes 
| 🔑 | pk&#95;lessons&#95;id || ON id|| Foreign Keys |  | fk_lessons_sections | ( section&#95;id ) ref [vlearn&#46;sections](#sections) (id) 
|| Options |
| 3 |


### Table live_lessons 
| Idx | Field Name | Data Type | Description |
|---|---|---|---|
| *🔑 ⬋ | <a name='vlearn.live_lessons_id'>id</a>| int UNSIGNED AUTO_INCREMENT |  |
| ⬈ | <a name='vlearn.live_lessons_section_id'>section&#95;id</a>| int UNSIGNED  |  |
|  | <a name='vlearn.live_lessons_name'>name</a>| varchar&#40;100&#41;  |  |
|  | <a name='vlearn.live_lessons_schedule_time'>schedule&#95;time</a>| bigint UNSIGNED  | timestamp |
| Indexes 
| 🔑 | pk&#95;live&#95;lessons&#95;id || ON id |  || Foreign Keys |  | fk_live_lessons_sections | ( section&#95;id ) ref [vlearn&#46;sections](#sections) (id) 
|  || Options |
| 4 |


### Table mentions 
| Idx | Field Name | Data Type |
|---|---|---|
| *🔑 | <a name='vlearn.mentions_id'>id</a>| int UNSIGNED  |
| ⬈ | <a name='vlearn.mentions_message_id'>message&#95;id</a>| int UNSIGNED  |
| ⬈ | <a name='vlearn.mentions_user_id'>user&#95;id</a>| int UNSIGNED  |
| Indexes 
| 🔑 | pk&#95;mentions&#95;id || ON id|| Foreign Keys |  | fk_mentions_messages | ( message&#95;id ) ref [vlearn&#46;messages](#messages) (id) 
||  | fk_mentions_users | ( user&#95;id ) ref [vlearn&#46;users](#users) (id) 
|| Options |
| 3 |


### Table messages 
| Idx | Field Name | Data Type |
|---|---|---|
| *🔑 ⬋ | <a name='vlearn.messages_id'>id</a>| int UNSIGNED AUTO_INCREMENT |
|  | <a name='vlearn.messages_sender_id'>sender&#95;id</a>| int UNSIGNED  |
| ⬈ | <a name='vlearn.messages_room_id'>room&#95;id</a>| int UNSIGNED  |
|  | <a name='vlearn.messages_content'>content</a>| long varchar  |
|  | <a name='vlearn.messages_create_at'>create&#95;at</a>| datetime  |
| Indexes 
| 🔑 | pk&#95;messages&#95;id || ON id|| Foreign Keys |  | fk_messages_rooms | ( room&#95;id ) ref [vlearn&#46;rooms](#rooms) (id) 
|| Options |
| 3 |


### Table questions 
&#43; type&#58;<br> &#45; 1&#58; lesson<br> &#45; 2&#58; question<br> &#45; 3&#58; live section<br>&#43; estimate&#95;time&#58;<br> &#45; lesson&#44; question&#58; estimate length<br> &#45; live section&#58; establish time

| Idx | Field Name | Data Type | Description |
|---|---|---|---|
| *🔑 ⬋ | <a name='vlearn.questions_id'>id</a>| int UNSIGNED AUTO_INCREMENT |  |
| *⬈ | <a name='vlearn.questions_section_id'>section&#95;id</a>| int UNSIGNED  |  |
|  | <a name='vlearn.questions_question'>question</a>| varchar&#40;100&#41;  |  |
| *| <a name='vlearn.questions_type'>type</a>| int UNSIGNED  |  |
| Indexes 
| 🔑 | pk&#95;learn&#95;unit&#95;id || ON id |  || Foreign Keys |  | fk_learn_unit_sections | ( section&#95;id ) ref [vlearn&#46;sections](#sections) (id) 
|  || Options |
| 4 |


### Table rooms 
type&#58;<br>&#45; 0&#58; course&#95;comment&#44;<br>&#45; 1&#58; lession chat<br>&#45; 2&#58; live chat<br>&#45; 3&#58; private chat &#45; refer null<br>&#45; 4&#58; group chat &#45; refer null

| Idx | Field Name | Data Type | Description |
|---|---|---|---|
| *🔑 ⬋ | <a name='vlearn.rooms_id'>id</a>| int UNSIGNED AUTO_INCREMENT |  |
|  | <a name='vlearn.rooms_name'>name</a>| varchar&#40;100&#41;  |  |
|  | <a name='vlearn.rooms_room_code'>room&#95;code</a>| varchar&#40;200&#41;  |  |
|  | <a name='vlearn.rooms_roomable_type'>roomable&#95;type</a>| varchar&#40;100&#41;  |  |
| ⬈ | <a name='vlearn.rooms_roomable_id'>roomable&#95;id</a>| int UNSIGNED  |  |
| Indexes 
| 🔑 | pk&#95;rooms&#95;id || ON id |  || Foreign Keys |  | fk_rooms_courses | ( roomable&#95;id ) ref [vlearn&#46;courses](#courses) (id) 
|  ||  | fk_rooms_lessons | ( roomable&#95;id ) ref [vlearn&#46;lessons](#lessons) (id) 
|  ||  | fk_rooms_live_lessons | ( roomable&#95;id ) ref [vlearn&#46;live&#95;lessons](#live&#95;lessons) (id) 
|  || Options |
| 4 |


### Table section_student 
| Idx | Field Name | Data Type |
|---|---|---|
| *🔑 ⬈ | <a name='vlearn.section_student_section_id'>section&#95;id</a>| int UNSIGNED  |
| *🔑 ⬈ | <a name='vlearn.section_student_student_id'>student&#95;id</a>| int UNSIGNED  |
| ⬈ | <a name='vlearn.section_student_lesson_checkpoint'>lesson&#95;checkpoint</a>| int UNSIGNED  |
|  | <a name='vlearn.section_student_highest_point'>highest&#95;point</a>| int  |
| Indexes 
| 🔑 | pk&#95;section&#95;student || ON section&#95;id&#44; student&#95;id|| 🔍  | unq&#95;section&#95;student&#95;section&#95;id || ON section&#95;id|| Foreign Keys |  | fk_section_student_sections | ( section&#95;id ) ref [vlearn&#46;sections](#sections) (id) 
||  | fk_section_student_users | ( student&#95;id ) ref [vlearn&#46;users](#users) (id) 
||  | fk_section_student_lessons | ( lesson&#95;checkpoint ) ref [vlearn&#46;lessons](#lessons) (id) 
|| Options |
| 3 |


### Table sections 
| Idx | Field Name | Data Type |
|---|---|---|
| *🔑 ⬋ | <a name='vlearn.sections_id'>id</a>| int UNSIGNED AUTO_INCREMENT |
| *⬈ | <a name='vlearn.sections_course_id'>course&#95;id</a>| int UNSIGNED  |
|  | <a name='vlearn.sections_name'>name</a>| varchar&#40;100&#41;  |
| Indexes 
| 🔑 | pk&#95;sections&#95;id || ON id|| Foreign Keys |  | fk_sections_courses | ( course&#95;id ) ref [vlearn&#46;courses](#courses) (id) 
|| Options |
| 3 |


### Table student_lesson 
check if student watch video or answer question&#44; time stamp watching and score

| Idx | Field Name | Data Type | Description |
|---|---|---|---|
| *⬈ | <a name='vlearn.student_lesson_student_id'>student&#95;id</a>| int UNSIGNED  |  |
| *⬈ | <a name='vlearn.student_lesson_lesson_id'>lesson&#95;id</a>| int UNSIGNED  |  |
| *| <a name='vlearn.student_lesson_is_touch'>is&#95;touch</a>| boolean  |  |
|  | <a name='vlearn.student_lesson_touch_value'>touch&#95;value</a>| varchar&#40;125&#41;  |  |
| Foreign Keys |  | fk_student_lesson_users | ( student&#95;id ) ref [vlearn&#46;users](#users) (id) 
|  ||  | fk_student_lessons | ( lesson&#95;id ) ref [vlearn&#46;lessons](#lessons) (id) 
|  || Options |
| 4 |


### Table students 
| Idx | Field Name | Data Type |
|---|---|---|
| *🔑 ⬈ | <a name='vlearn.students_user_id'>user&#95;id</a>| int UNSIGNED  |
|  | <a name='vlearn.students_receive_mail'>receive&#95;mail</a>| varchar&#40;250&#41;  |
|  | <a name='vlearn.students_receive_flower_new_course'>receive&#95;flower&#95;new&#95;course</a>| boolean  |
|  | <a name='vlearn.students_receive_notificate'>receive&#95;notificate</a>| boolean  |
|  | <a name='vlearn.students_receive_course_change'>receive&#95;course&#95;change</a>| boolean  |
| Indexes 
| 🔑 | pk&#95;students&#95;user&#95;id || ON user&#95;id|| Foreign Keys |  | fk_students_users | ( user&#95;id ) ref [vlearn&#46;users](#users) (id) 
|

### Table topics 
| Idx | Field Name | Data Type |
|---|---|---|
| *🔑 ⬋ | <a name='vlearn.topics_id'>id</a>| int UNSIGNED AUTO_INCREMENT |
|  | <a name='vlearn.topics_name'>name</a>| varchar&#40;100&#41;  |
|  | <a name='vlearn.topics_request_id'>request&#95;id</a>| int UNSIGNED  |
|  | <a name='vlearn.topics_commit_id'>commit&#95;id</a>| int UNSIGNED  |
| ⬈ | <a name='vlearn.topics_parent_id'>parent&#95;id</a>| int UNSIGNED  |
| Indexes 
| 🔑 | pk&#95;topics&#95;id || ON id|| Foreign Keys |  | fk_topics_parent | ( parent&#95;id ) ref [vlearn&#46;topics](#topics) (id) 
|| Options |
| 3 |


### Table user_room 
| Idx | Field Name | Data Type | Description |
|---|---|---|---|
| *🔑 ⬈ | <a name='vlearn.user_room_user_id'>user&#95;id</a>| int UNSIGNED  |  |
| *🔑 ⬈ | <a name='vlearn.user_room_room_id'>room&#95;id</a>| int UNSIGNED  |  |
|  | <a name='vlearn.user_room_status'>status</a>| int  | 1&#45; normal<br>0&#45; mute |
| Indexes 
| 🔑 | pk&#95;user&#95;room || ON user&#95;id&#44; room&#95;id |  || Foreign Keys |  | fk_user_room_rooms | ( room&#95;id ) ref [vlearn&#46;rooms](#rooms) (id) 
|  ||  | fk_user_room_users | ( user&#95;id ) ref [vlearn&#46;users](#users) (id) 
|  || Options |
| 4 |


### Table user_topic 
| Idx | Field Name | Data Type |
|---|---|---|
| *🔑 ⬈ | <a name='vlearn.user_topic_user_id'>user&#95;id</a>| int UNSIGNED  |
| *🔑 ⬈ | <a name='vlearn.user_topic_topic_id'>topic&#95;id</a>| int UNSIGNED  |
| Indexes 
| 🔑 | pk&#95;user&#95;topic || ON user&#95;id&#44; topic&#95;id|| Foreign Keys |  | fk_user_topic_users | ( user&#95;id ) ref [vlearn&#46;users](#users) (id) 
||  | fk_user_topic_topics | ( topic&#95;id ) ref [vlearn&#46;topics](#topics) (id) 
|| Options |
| 3 |


### Table users 
| Idx | Field Name | Data Type |
|---|---|---|
| *🔑 ⬋ | <a name='vlearn.users_id'>id</a>| int UNSIGNED AUTO_INCREMENT |
|  | <a name='vlearn.users_username'>username</a>| varchar&#40;100&#41;  |
|  | <a name='vlearn.users_email'>email</a>| varchar&#40;100&#41;  |
|  | <a name='vlearn.users_name'>name</a>| varchar&#40;100&#41;  |
|  | <a name='vlearn.users_password'>password</a>| varchar&#40;100&#41;  |
|  | <a name='vlearn.users_confirmation_code'>confirmation&#95;code</a>| int  |
|  | <a name='vlearn.users_email_verify_at'>email&#95;verify&#95;at</a>| date  |
|  | <a name='vlearn.users_avatar'>avatar</a>| varchar&#40;300&#41;  |
|  | <a name='vlearn.users_role'>role</a>| tinyint  |
| Indexes 
| 🔑 | pk&#95;users&#95;id || ON id|| Options |
| 3 |





