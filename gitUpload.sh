getMessage(){

	pusheddate=""
	
	currentDate=`date`

	for i in $currentDate

		do

			if [ $i = 'IST' ] 
	
			then 
		
				continue
	
			fi
	
			pusheddate="$pusheddate$i\|"

		done

		eval "$1=$pusheddate"

}

add(){
echo "adding!!!!!!!!!!!!!!!!!!!\n\n"

git add .

echo 'Adding done....\n\n'

}


commit(){
	echo "commiting!!!!!!!!!!!!!!!!!!!!\n\n"
	
	getMessage message

	git commit -m $message


	echo 'Commit done...\n\n'
}

pull(){
	echo 'Pulling !!!!!!!!!!!!!!!!!\n\n'

	git pull origin master

	echo 'Pull done..\n\n'
}


push(){
	echo "pushing!!!!!!!!!!!!!!!!!!!!!!!!!!\n\n"

	git push origin master
	
	echo 'Push done...\n\n'
}

upload(){
	git init
	add
	commit
	git remote add origin https://github.com/shanmukha505/Music_Player_vl.git
	pull
	push


	echo 'Upload done...\n\n'
}

upload
