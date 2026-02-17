
<?php

test('listings page loads',function(){
$this->get('/listings')->assertStatus(200);
});
