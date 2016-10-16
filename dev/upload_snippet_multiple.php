               $categs = array();
                if ($result2->num_rows > 0) {
                     while($row = $result2->fetch_assoc()) {
                            foreach ($categories as  $cats) {
                                $photo = $row["photo"] ;
                                $keywords1 = explode(",", $row["category"]); 
                                 $images1 = array();

                                foreach ($keywords1 as  $value3) {
                                    if (!empty($value3) && ($value3 == $cats) )
                                    {   
                                         if !(in_array($photo, $images1))   
                                            array_push($images1, $photo);  
                                    }
                                $categs[$value3] = $images1;
                                }
                                // if (strpos($row["category"],  $value2)  ) {
                                        // $photo = $row["photo"] ;
                                        // $tags = $row["category"];
                                        // $images1[$value2] = $photo;
                                        // array_push($images1, $value2 );
                                // }    
                            }
                           // $keyword1= $row["keyword"] ;
                           // $tags = $row["categories"];
                           // $images1[$keyword1] = $tags;
                    }
                }