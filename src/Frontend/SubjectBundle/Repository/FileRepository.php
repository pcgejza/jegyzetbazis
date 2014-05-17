<?php

namespace Frontend\SubjectBundle\Repository;

use Doctrine\ORM\EntityRepository;

class FileRepository extends EntityRepository {

    public function getFilesByIdsArray($array) {
        return $this->createQueryBuilder('file')
                        ->select('file,subjectFile')
                        ->where('file.id IN(:ids)')
                        ->setParameter('ids', $array)
                        ->leftJoin('file.subjectFile', 'subjectFile')
                        ->getQuery()
                        ->getResult();
    }

    public function getFilesToPageBySubject($Subject, $user, $page = 1, $limit = 10) {
        $Files = $this->createQueryBuilder('file')
                ->select('file, user, userSettings')
                ->where('subjectFile.subject = :subject')
                ->andWhere('file.status = 1')
                ->join('file.subjectFile', 'subjectFile')
                ->leftJoin('file.user', 'user')
                ->leftJoin('user.userSettings', 'userSettings')
                ->setParameter('subject', $Subject)
                ->orderBy('file.uploadedTime', 'DESC')
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();

        return $Files;
    }

    public function getFilesToPageBySubjectQUERY($Subject, $user, $filters = array()) {
        $Files = $this->createQueryBuilder('file')
                ->select('file, user, userSettings')
                ->where('subjectFile.subject = :subject')
                ->andWhere('file.status = 1')
                ->join('file.subjectFile', 'subjectFile')
                ->leftJoin('file.user', 'user')
                ->leftJoin('user.userSettings', 'userSettings')
                ->setParameter('subject', $Subject)
                ->orderBy('file.uploadedTime', 'DESC');

        if (isset($filters['sortBy'])) {
            switch ($filters['sortBy']) {
                case 'showNew':
                    $Files = $Files->orderBy('file.uploadedTime', 'DESC');
                    break;
                case 'showOld':
                    $Files = $Files->orderBy('file.uploadedTime', 'ASC');
                    break;
                case 'showNameAsc':
                    $Files = $Files->orderBy('file.name', 'ASC');
                    break;
                case 'showNameDesc':
                    $Files = $Files->orderBy('file.name', 'DESC');
                    break;
            }
        }

        return $Files->getQuery();
    }

    public function getFilesCountByUser($User) {
        return $this->createQueryBuilder('file')
                        ->select('COUNT(file.id)')
                        ->where('user = :user')
                        ->andWhere('file.status = 1')
                        ->join('file.user', 'user')
                        ->setParameter('user', $User)
                        ->getQuery()
                        ->getSingleScalarResult();
    }

    /*
     * getFileById - a fájl lekérdezése ID alapján //nem figyeli azt ha valaki nem engedi az adatai megtekintését
     */

    public function getFileById($fileId, $User = null) {
        $File = $this->createQueryBuilder('file')
                ->select('file')
                ->addSelect('user')
                ->addSelect('userSettings')
                ->addSelect('subjects')
                ->where('file.id = :fileId')
                ->andWhere('file.status = 1')
                ->leftJoin('file.subjects', 'subjects', 'WITH', 'subjects.status = 1')
                ->join('file.user', 'user')
                ->leftJoin('user.userSettings', 'userSettings')
                ->setParameter('fileId', $fileId)
                ->getQuery()
                ->getOneOrNullResult();

        return $File;
    }
    
    public function getFilesByUser($User){
           $Files = $this->createQueryBuilder('file')
                ->select('file')
                ->addSelect('subjects')
                ->where('user = :User')
                ->andWhere('file.status = 1')
                ->leftJoin('file.subjects', 'subjects', 'WITH', 'subjects.status = 1')
                ->join('file.user', 'user')
                ->leftJoin('user.userSettings', 'userSettings')
                ->setParameter('User', $User)
                ->getQuery()
                ->getResult();

        return $Files;
    }

}
